<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            // Store formatted username in session
            $user = Auth::user();
            $username = strtoupper(substr($user->first_name, 0, 1)) . '_' . $user->last_name;
            session(['username' => $username]);

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

        Auth::login($user);
        $request->session()->regenerate();

        // Store formatted username in session after signup
        $username = strtoupper(substr($user->first_name, 0, 1)) . '_' . $user->last_name;
        session(['username' => $username]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show.login');
    }

}
