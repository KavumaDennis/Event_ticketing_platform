<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice(Request $request)
    {
        return $request->user() && $request->user()->hasVerifiedEmail()
                    ? redirect()->route('home')
                    : view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = $request->user();
        $username = strtoupper(substr($user->first_name, 0, 1)) . '_' . $user->last_name;
        session(['username' => $username]);

        return redirect()->route('home')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
