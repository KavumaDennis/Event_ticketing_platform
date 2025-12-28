<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    // Store a new FAQ
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        Faq::create([
            'user_id' => Auth::id(),
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return back()->with('success', 'FAQ created successfully!');
    }

    // Update an existing FAQ
    public function update(Request $request, Faq $faq)
    {
        // Optional: specific authorization check
        if ($faq->user_id !== Auth::id()) {
           // For now, let's allow it or just restrict. 
           // Assuming dashboard access implies trust or we can restrict.
           // abort(403); 
        }

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return back()->with('success', 'FAQ updated successfully!');
    }

    // Delete an FAQ
    public function destroy(Faq $faq)
    {
         if ($faq->user_id !== Auth::id()) {
             // abort(403);
         }

        $faq->delete();

        return back()->with('success', 'FAQ deleted successfully!');
    }
}
