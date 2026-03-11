<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\TicketPurchase;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TierController extends Controller
{
    protected $flutterwave;

    public function __construct(FlutterwaveService $flutterwave)
    {
        $this->flutterwave = $flutterwave;
    }

    public function buyTier(Request $request)
    {
        $request->validate([
            'tier' => 'required|in:pro,elite',
        ]);

        $user = Auth::user();
        $organizer = $user->organizer;

        if (!$organizer) {
            return redirect()->route('organizer.signup')->with('error', 'Please register as an organizer first.');
        }

        $prices = [
            'pro'   => 50000,   // UGX 50,000
            'elite' => 150000,  // UGX 150,000
        ];

        $amount = $prices[$request->tier];
        $txRef = 'tier-' . $organizer->id . '-' . $request->tier . '-' . Str::random(10);

        // We can reuse TicketPurchase table or create a separate one. 
        // For simplicity, let's assume we use a generic payment flow or just initialize directly.
        // Actually, creating a TicketPurchase entry with a specific type might be hacky.
        // Let's just initialize the payment.

        $paymentData = [
            'tx_ref'       => $txRef,
            'amount'       => $amount,
            'currency'     => 'UGX',
            'redirect_url' => route('organizer.tier.callback'),
            'email'        => $user->email,
            'phone'        => '', // Optional
            'name'         => $user->first_name . ' ' . $user->last_name,
            'description'  => "Upgrade to " . ucfirst($request->tier) . " Tier",
        ];

        $payment = $this->flutterwave->initializePayment($paymentData);

        if (isset($payment['status']) && $payment['status'] === 'success') {
            return redirect($payment['data']['link']);
        }

        return redirect()->back()->with('error', 'Failed to initialize payment with Flutterwave');
    }

    public function callback(Request $request)
    {
        $status = $request->status;
        $txRef = $request->tx_ref; // e.g., tier-1-pro-random
        $transactionId = $request->transaction_id;

        if ($status === 'successful' || $status === 'completed') {
            $verification = $this->flutterwave->verifyTransaction($transactionId);

            if ($verification && isset($verification['status']) && $verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
                
                // Parse txRef: tier-{organizer_id}-{tier}-random
                $parts = explode('-', $txRef);
                if (count($parts) >= 3 && $parts[0] === 'tier') {
                    $organizerId = $parts[1];
                    $tier = $parts[2];

                    $organizer = Organizer::find($organizerId);
                    if ($organizer) {
                        $organizer->update(['tier' => $tier]);
                        return redirect()->route('user.dashboard.overview')->with('success', "Upgraded to " . ucfirst($tier) . " successfully!");
                    }
                }
            }
        }

        return redirect()->route('organizer.create')->with('error', 'Payment failed or was cancelled.');
    }
}
