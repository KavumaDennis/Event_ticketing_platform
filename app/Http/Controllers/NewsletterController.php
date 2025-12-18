<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Check if email already exists
        $existing = NewsletterSubscription::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active) {
                return redirect()->back()->with('newsletter_error', 'This email is already subscribed to our newsletter.');
            } else {
                // Reactivate subscription
                $existing->update([
                    'is_active' => true,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                ]);
                return redirect()->back()->with('newsletter_success', 'Welcome back! You have been resubscribed to our newsletter.');
            }
        }

        // Create new subscription
        NewsletterSubscription::create([
            'email' => $validated['email'],
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        return redirect()->back()->with('newsletter_success', 'Thank you for subscribing to our newsletter!');
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $email)
    {
        $subscription = NewsletterSubscription::where('email', $email)->first();

        if ($subscription && $subscription->is_active) {
            $subscription->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);

            return redirect()->back()->with('newsletter_success', 'You have been unsubscribed from our newsletter.');
        }

        return redirect()->back()->with('newsletter_error', 'Email not found or already unsubscribed.');
    }
}
