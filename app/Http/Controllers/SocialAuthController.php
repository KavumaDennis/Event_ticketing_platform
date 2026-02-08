<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update user with google_id and avatar if not set
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Create a new user
                $names = explode(' ', $googleUser->getName(), 2);
                $firstName = $names[0] ?? 'Google';
                $lastName = $names[1] ?? 'User';
                
                $username = strtolower($firstName . $lastName . Str::random(4));

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->getEmail(),
                    'username' => $username,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)), // Dummy password
                ]);
            }

            Auth::login($user);

            // Store formatted username in session
            $username = strtoupper(substr($user->first_name, 0, 1)) . '_' . $user->last_name;
            session(['username' => $username]);

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('show.login')->with('error', 'Something went wrong with Google Login!');
        }
    }
}
