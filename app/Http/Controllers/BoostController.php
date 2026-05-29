<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\FlutterwaveService;
use App\Services\MtnService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BoostController extends Controller
{
    protected $flutterwave;
    protected $mtn;

    public function __construct(FlutterwaveService $flutterwave, MtnService $mtn)
    {
        $this->flutterwave = $flutterwave;
        $this->mtn = $mtn;
    }

    public function selectPlan(Event $event)
    {
        // Ensure user owns the event
        if ($event->organizer->user_id !== auth()->id()) {
            abort(403);
        }

        $plans = collect(config('monetization.boosting', []))
            ->only(['24_hours', '7_days', '30_days'])
            ->toArray();

        return view('dashboard.boost.select-plan', compact('event', 'plans'));
    }

    public function initialize(Request $request, Event $event)
    {
        $request->validate([
            'plan' => 'required|in:24_hours,7_days,30_days',
            'payment_method' => 'required|in:momo,flutterwave',
            'phone' => 'required_if:payment_method,momo',
        ]);

        if ($event->organizer->user_id !== auth()->id()) {
            abort(403);
        }

        $plans = collect(config('monetization.boosting', []))
            ->only(['24_hours', '7_days', '30_days'])
            ->toArray();
        $amount = $plans[$request->plan] ?? null;
        if ($amount === null) {
            return back()->with('error', 'Invalid boost plan selected.');
        }
        $txRef = 'boost-' . $event->id . '-' . $request->plan . '-' . Str::random(6);

        if ($request->payment_method === 'momo') {
            try {
                $referenceId = $this->mtn->requestPayment(
                    $request->phone,
                    $amount,
                    $txRef
                );

                if (str_starts_with($referenceId, 'error:')) {
                    return back()->with('error', 'MTN Error: ' . $referenceId);
                }

                session([
                    'boost_payment' => [
                        'reference_id' => $referenceId,
                        'tx_ref' => $txRef,
                        'event_id' => $event->id,
                        'plan' => $request->plan,
                    ],
                ]);

                return view('dashboard.boost.waiting-momo', [
                    'event' => $event,
                    'txRef' => $txRef,
                ]);

            } catch (\Exception $e) {
                return back()->with('error', 'Boost initialization failed: ' . $e->getMessage());
            }
        } else {
            // Flutterwave
            $paymentData = [
                'tx_ref'       => $txRef,
                'amount'       => $amount,
                'currency'     => 'UGX',
                'redirect_url' => route('boost.callback'),
                'email'        => auth()->user()->email,
                'phone'        => auth()->user()->phone,
                'name'         => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'description'  => "Boosting event: " . $event->event_name . " (" . $request->plan . ")",
            ];

            $payment = $this->flutterwave->initializePayment($paymentData);

            if (isset($payment['status']) && $payment['status'] === 'success') {
                return redirect($payment['data']['link']);
            }

            return back()->with('error', 'Flutterwave initialization failed.');
        }
    }

    public function callback(Request $request)
    {
        $status = $request->status;
        $txRef = $request->tx_ref; // boost-eventid-plan-random
        $transactionId = $request->transaction_id;

        if ($status === 'successful' || $status === 'completed') {
            $verification = $this->flutterwave->verifyTransaction($transactionId);

            if ($verification && isset($verification['status']) && $verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
                
                $this->applyBoost($txRef);

                return redirect()->route('user.dashboard.overview')->with('success', 'Event boosted successfully!');
            }
        }

        return redirect()->route('user.dashboard.overview')->with('error', 'Boost payment failed.');
    }

    public function momoStatus(Event $event)
    {
        $sessionData = session('boost_payment');
        if (!$sessionData || ($sessionData['event_id'] ?? null) !== $event->id) {
            return response()->json(['status' => 'error', 'message' => 'Boost session not found.'], 404);
        }

        try {
            $status = $this->mtn->getPaymentStatus($sessionData['reference_id']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to check payment status.'], 500);
        }

        $remoteStatus = strtoupper($status['status'] ?? 'PENDING');

        if ($remoteStatus === 'SUCCESSFUL') {
            $this->applyBoost($sessionData['tx_ref']);
            session()->forget('boost_payment');

            return response()->json([
                'status' => 'paid',
                'redirect' => route('user.dashboard.events'),
            ]);
        }

        if ($remoteStatus === 'FAILED') {
            session()->forget('boost_payment');
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
    }

    protected function applyBoost($txRef)
    {
        $parts = explode('-', $txRef);
        if (count($parts) >= 3 && $parts[0] === 'boost') {
            $eventId = $parts[1];
            $plan = $parts[2];

            $event = Event::find($eventId);
            if ($event) {
                $days = 0;
                if ($plan === '24_hours') $days = 1;
                elseif ($plan === '7_days') $days = 7;
                elseif ($plan === '30_days') $days = 30;

                $event->update([
                    'is_boosted' => true,
                    'boosted_until' => now()->addDays($days),
                ]);
            }
        }
    }
}
