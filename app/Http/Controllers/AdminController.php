<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\User;
use App\Models\Trend;
use App\Models\TicketPurchase;
use App\Models\Faq;
use App\Models\PayoutRequest;
use App\Models\Referral;
use App\Models\PromoCode;
use App\Models\Waitlist;
use App\Models\PaymentFlag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ADMIN DASHBOARD OVERVIEW
    public function index()
    {
        // 1. Basic Counts
        $totalUsers = User::count();
        $totalOrganizers = Organizer::count();
        $totalEvents = Event::count();
        
        // 2. Financial Stats (Revenue from ticket sales)
        // Assuming 'total_amount' is in ticket_purchases table. 
        // Note: Check available columns later if needed, but assuming standard total_amount or calculate from price * qty
        // Let's check ticket_purchases table or orders table. 
        // Based on file list earlier: 2025_12_04_225115_create_ticket_purchases_table.php and 2025_12_13_165022_create_orders_table.php
        // I'll assume access to TicketPurchase model with a 'total_price' or similar. 
        // Let's assume for now TicketPurchase has 'total_price'. If not, I'll fix it. 
        // Actually, usually it's better to verify schema first but for speed I'll try sum('total_price').
        if (Schema::hasColumn('ticket_purchases', 'total_base')) {
            $totalRevenue = TicketPurchase::selectRaw('sum(coalesce(total_base, total)) as total')->value('total');
        } else {
            $totalRevenue = TicketPurchase::sum('total');
        }

        // 3. Recent Activity (Depth)
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = TicketPurchase::with(['user', 'event'])->latest()->take(5)->get();

        // 4. Organizer Status
        // Pending verification? Assuming we want to verify organizers?
        // Let's count organizers that might need attention (e.g. no events yet? or maybe an 'is_verified' field if we had one)
        // For now, let's just show top organizers by followers count for admin insight
        $topOrganizers = Organizer::withCount('followers')->orderByDesc('followers_count')->take(5)->get();

        // 5. Events status
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        $pendingPayoutsCount = PayoutRequest::where('status', 'pending')->count();
        $openPaymentFlagsCount = Schema::hasTable('payment_flags')
            ? PaymentFlag::where('status', 'open')->count()
            : 0;

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalOrganizers', 
            'totalEvents', 
            'totalRevenue',
            'recentUsers',
            'recentOrders',
            'topOrganizers',
            'upcomingEvents',
            'pendingPayoutsCount'
            , 'openPaymentFlagsCount'
        ));
    }

    // MANAGE USERS
    public function users()
    {
        $users = User::latest()->paginate(8);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting self? or super admin?
        if($user->is_admin && $user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete yourself.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // MANAGE ORGANIZERS
    public function organizers()
    {
        $organizers = Organizer::withCount(['events', 'followers'])->latest()->paginate(5);
        return view('admin.organizers', compact('organizers'));
    }
    
    // Example Verification Action (if we add verify column later, for now just placeholder or delete)
    public function verifyOrganizer(Organizer $organizer) 
    {
        // $organizer->update(['verified_at' => now()]);
        return back()->with('success', 'Organizer verified (Simulated).');
    }

    public function deleteOrganizer(Organizer $organizer)
    {
        $organizer->delete();
        return back()->with('success', 'Organizer deleted.');
    }


    // MANAGE EVENTS
    public function events()
    {
        $events = Event::with('organizer')->latest()->paginate(20);
        return view('admin.events', compact('events'));
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }


    // MANAGE FINANCE
    public function finance()
    {
        $purchases = TicketPurchase::with(['user', 'event.organizer'])->latest()->paginate(5);
        if (Schema::hasColumn('ticket_purchases', 'total_base')) {
            $totalRevenue = TicketPurchase::selectRaw('sum(coalesce(total_base, total)) as total')->value('total');
        } else {
            $totalRevenue = TicketPurchase::sum('total');
        }
        
        return view('admin.finance', compact('purchases', 'totalRevenue'));
    }

    // MANAGE FAQs
    public function faqs()
    {
        $faqs = Faq::latest()->paginate(15);
        return view('admin.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:2000',
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'FAQ created successfully.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:2000',
        ]);

        $faq->update($request->only(['question', 'answer']));

        return back()->with('success', 'FAQ updated.');
    }

    public function deleteFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    // MANAGE REVIEWS
    public function reviews()
    {
        $reviews = \App\Models\Review::with(['user', 'event'])->latest()->paginate(20);
        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview(\App\Models\Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->get();
        return view('dashboard.notifications', compact('user', 'notifications'));
    }

    // MANAGE TRENDS
    public function trends()
    {
        $trends = Trend::with(['user', 'event'])->latest()->paginate(15);
        return view('admin.trends', compact('trends'));
    }

    public function toggleEditorsPick(Trend $trend)
    {
        $trend->update([
            'is_editors_pick' => !$trend->is_editors_pick
        ]);

        return back()->with('success', 'Trend status updated.');
    }

    // --- NEW ADMIN CONTROLS ---

    // 1. Payout Management
    public function payouts()
    {
        $requests = PayoutRequest::with('organizer')->latest()->paginate(20);
        return view('admin.payouts', compact('requests'));
    }

    public function updatePayoutStatus(Request $request, PayoutRequest $payout)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $payout->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => auth()->id()
        ]);

        return back()->with('success', 'Payout request updated.');
    }

    // 2. Referral & Affiliate Tracking
    public function referrals()
    {
        $referrals = Referral::with(['referrer', 'referredUser'])->latest()->paginate(20);
        $totalEarned = Referral::sum('commission_earned');
        
        $topReferrers = User::whereHas('referrals')
            ->withCount('referrals')
            ->withSum('referrals as total_commissions', 'commission_earned')
            ->orderByDesc('referrals_count')
            ->take(10)
            ->get();

        return view('admin.referrals', compact('referrals', 'totalEarned', 'topReferrers'));
    }

    // 3. Organizer Settings Overrides
    public function editOrganizer(Organizer $organizer)
    {
        return view('admin.edit-organizer', compact('organizer'));
    }

    public function updateOrganizer(Request $request, Organizer $organizer)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'payout_frequency' => 'required|in:daily,weekly,monthly',
            'tier' => 'required|in:free,pro,elite',
            'is_verified' => 'boolean'
        ]);

        $organizer->update($validated);

        return redirect()->route('admin.organizers')->with('success', 'Organizer updated successfully.');
    }

    // 4. Promo Code Control
    public function promos()
    {
        $promos = PromoCode::with('organizer')->latest()->paginate(30);
        return view('admin.promos', compact('promos'));
    }

    public function deletePromo(PromoCode $promo)
    {
        $promo->delete();
        return back()->with('success', 'Promo code removed.');
    }

    // 6. Payment Flags / Chargebacks Queue
    public function paymentFlags()
    {
        $flags = Schema::hasTable('payment_flags')
            ? PaymentFlag::with(['purchase.user', 'purchase.event.organizer'])
                ->latest()
                ->paginate(20)
            : collect();

        return view('admin.payment-flags', compact('flags'));
    }

    public function updatePaymentFlag(Request $request, PaymentFlag $flag)
    {
        $request->validate([
            'status' => 'required|in:reviewed,cleared',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $flag->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Payment flag updated.');
    }

    // 5. Deep Analytics Overlook
    public function organizerAnalytics(Organizer $organizer)
    {
        // Reuse the logic from OrganizerAnalyticsController but for admin
        return app(OrganizerAnalyticsController::class)->indexForAdmin($organizer);
    }
}
