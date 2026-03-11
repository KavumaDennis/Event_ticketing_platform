<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //


    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function showSignup()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('signup');
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($validated, $remember)) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            $request->session()->regenerate();

            // Store formatted username in session
            $user = Auth::user();
            $username = strtoupper(substr($user->first_name, 0, 1)) . '_' . $user->last_name;
            session(['username' => $username]);

            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'email' => 'Sorry, incorrect email or password.'
        ]);
    }


    public function signup(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:4|max:255',
            'last_name' => 'required|string|min:4|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->ref) {
            $referrer = User::where('referral_code', $request->ref)->first();
            if ($referrer) {
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                ]);

                // Notify Referrer
                \App\Models\Notification::create([
                    'user_id' => $referrer->id,
                    'title' => 'New Referral!',
                    'message' => $user->first_name . " joined using your referral code. You'll earn commissions on their ticket purchases!",
                    'type' => 'success',
                ]);
            }
        }

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show.login');
    }

}
