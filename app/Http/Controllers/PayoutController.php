<?php

namespace App\Http\Controllers;

use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;
        if (!$organizer) {
            return redirect()->route('organizer.signup');
        }

        $balance = $organizer->getAvailableBalance();
        $payoutRequests = $organizer->payoutRequests()->latest()->get();

        return view('dashboard.payouts.index', compact('organizer', 'balance', 'payoutRequests'));
    }

    public function store(Request $request)
    {
        $organizer = auth()->user()->organizer;
        if (!$organizer) abort(403);

        $balance = $organizer->getAvailableBalance();

        $request->validate([
            'amount' => "required|numeric|min:5000|max:$balance",
            'payment_method' => 'required|in:momo,bank',
        ]);

        $details = ($request->payment_method === 'momo') 
            ? "MoMo: " . ($organizer->payout_mobile_money_number ?? auth()->user()->phone)
            : "Bank: " . ($organizer->payout_bank_name . " - " . $organizer->payout_account_number);

        PayoutRequest::create([
            'organizer_id' => $organizer->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_details' => $details,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Payout request submitted successfully!');
    }
}
