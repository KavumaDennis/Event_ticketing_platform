<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayoutController extends Controller
{
    public function index()
    {
        $organizer = Organizer::forManagingUser(auth()->user());
        if (! $organizer) {
            return redirect()->route('organizer.create');
        }

        $user = auth()->user();
        $ownsProfile = (int) $organizer->user_id === (int) $user->id;
        if (! $ownsProfile && ! $organizer->hasRole($user, ['owner', 'finance'])) {
            abort(403);
        }

        $balance = $organizer->getAvailableBalance();
        $payoutRequests = $organizer->payoutRequests()->latest()->get();
        $payoutFrequency = $organizer->payout_frequency ?? 'monthly';

        $now = Carbon::now();
        if ($payoutFrequency === 'daily') {
            $nextPayoutDate = $now->copy()->addDay()->startOfDay();
        } elseif ($payoutFrequency === 'weekly') {
            $nextPayoutDate = $now->copy()->addWeek()->startOfDay();
        } else {
            $nextPayoutDate = $now->copy()->addMonthNoOverflow()->startOfMonth();
        }

        return view('dashboard.payouts.index', compact(
            'organizer',
            'balance',
            'payoutRequests',
            'payoutFrequency',
            'nextPayoutDate'
        ));
    }

    public function store(Request $request)
    {
        $organizer = Organizer::forManagingUser(auth()->user());
        if (! $organizer) {
            abort(403);
        }

        $user = auth()->user();
        $ownsProfile = (int) $organizer->user_id === (int) $user->id;
        if (! $ownsProfile && ! $organizer->hasRole($user, ['owner', 'finance'])) {
            abort(403);
        }

        $balance = $organizer->getAvailableBalance();

        $request->validate([
            'amount' => "required|numeric|min:5000|max:$balance",
            'payment_method' => 'required|in:momo,bank',
        ]);

        if ($request->payment_method === 'momo') {
            $details = 'MoMo: ' . ($organizer->payout_mobile_money_number ?? auth()->user()->phone);
        } else {
            $details = trim(
                implode(' • ', array_filter([
                    $organizer->payout_bank_name,
                    $organizer->payout_account_number,
                    $organizer->payout_account_name,
                ]))
            ) ?: 'Bank (details not set)';
        }

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
