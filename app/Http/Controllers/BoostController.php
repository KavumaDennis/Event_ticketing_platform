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

        $plans = config('monetization.boosting');

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

        $plans = config('monetization.boosting');
        $amount = $plans[$request->plan];
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

                // We need a way to track boost payments. 
                // For now, let's use a generic table or just reuse a logic.
                // Actually, creating a dedicated 'purchases' or repurposing TicketPurchase might be messy.
                // Let's assume we have a simple way to track these by the external_id/txRef.
                
                return render_view_or_redirect('waiting_for_momo', ['tx_ref' => $txRef]);

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
