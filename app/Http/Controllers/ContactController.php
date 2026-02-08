<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function index(){
        $faqs = \App\Models\Faq::all();
        return view('contact', compact('faqs'));
    }

    public function submit(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000'
        ]);

        // Prepend subject and priority if coming from dashboard support
        if ($request->has('subject') || $request->has('priority')) {
            $prefix = "--- SUPPORT TICKET DETAILS ---\n";
            $prefix .= "Subject: " . $request->input('subject', 'N/A') . "\n";
            $prefix .= "Priority: " . $request->input('priority', 'Normal') . "\n";
            $prefix .= "-----------------------------\n\n";
            $validated['message'] = $prefix . $validated['message'];
        }

        Contact::create($validated);

        return back()->with('success', 'Your message has been sent successfully');
    }
}
