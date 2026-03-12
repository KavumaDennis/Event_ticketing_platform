<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AdminController,
    AuthController,
    ContactController,
    EventsController,
    LikeController,
    NewsletterController,
    OrganizerAnalyticsController,
    OrganizersController,
    SavedEventController,
    TrendsController,
    FaqController,
    HomeController,
    OrganizerFollowController,
    MomoController,
    PaymentController,
    TicketController,
    UserController,
    DashboardController,
    ForgotPasswordController,
    SocialAuthController,
    VerificationController,
    FlutterwaveController,
    TierController,
    BoostController,
    PayoutController,
    WaitlistController,
    ExperienceController,
    OrganizerTeamController,
    TagController,
    DiscoveryController
};

// ADMIN DASHBOARD
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    Route::get('/organizers', [AdminController::class, 'organizers'])->name('admin.organizers');
    Route::post('/organizers/{organizer}/verify', [AdminController::class, 'verifyOrganizer'])->name('admin.organizers.verify');
    Route::delete('/organizers/{organizer}', [AdminController::class, 'deleteOrganizer'])->name('admin.organizers.delete');

    Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('admin.events.delete');

    Route::get('/finance', [AdminController::class, 'finance'])->name('admin.finance');

    Route::get('/faqs', [AdminController::class, 'faqs'])->name('admin.faqs');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::put('/faqs/{faq}', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq}', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');

    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::delete('/reviews/{review}', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');

    // Trends Management
    Route::get('/trends', [AdminController::class, 'trends'])->name('admin.trends');
    Route::post('/trends/{trend}/toggle-editors-pick', [AdminController::class, 'toggleEditorsPick'])->name('admin.trends.toggle-editors-pick');

    // New Management Suite
    Route::get('/payouts', [AdminController::class, 'payouts'])->name('admin.payouts');
    Route::post('/payouts/{payout}/status', [AdminController::class, 'updatePayoutStatus'])->name('admin.payouts.update-status');

    Route::get('/referrals', [AdminController::class, 'referrals'])->name('admin.referrals');

    Route::get('/organizers/{organizer}/edit', [AdminController::class, 'editOrganizer'])->name('admin.organizers.edit');
    Route::put('/organizers/{organizer}', [AdminController::class, 'updateOrganizer'])->name('admin.organizers.update');
    Route::get('/organizers/{organizer}/analytics', [AdminController::class, 'organizerAnalytics'])->name('admin.organizers.analytics');

    Route::get('/promos', [AdminController::class, 'promos'])->name('admin.promos');
    Route::delete('/promos/{promo}', [AdminController::class, 'deletePromo'])->name('admin.promos.delete');

    Route::get('/payment-flags', [AdminController::class, 'paymentFlags'])->name('admin.payment-flags');
    Route::post('/payment-flags/{flag}', [AdminController::class, 'updatePaymentFlag'])->name('admin.payment-flags.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'overview'])->name('user.dashboard.overview'); // A - sidebar
    Route::get('/dashboard/organizer/settings', [DashboardController::class, 'organizerSettings'])->name('organizer.settings');
    Route::post('/dashboard/organizer/settings', [DashboardController::class, 'updateOrganizerSettings'])->name('organizer.settings.update');
    Route::post('/dashboard/organizer/team', [OrganizerTeamController::class, 'add'])->name('organizer.team.add');
    Route::put('/dashboard/organizer/team/{member}', [OrganizerTeamController::class, 'updateRole'])->name('organizer.team.update');
    Route::delete('/dashboard/organizer/team/{member}', [OrganizerTeamController::class, 'remove'])->name('organizer.team.remove');
    Route::get('/dashboard/organizer/profile', [DashboardController::class, 'organizerProfile'])->name('user.dashboard.organizer-profile');
    Route::get('/user/dashboard/following', [DashboardController::class, 'following'])->name('user.dashboard.following');
    Route::get('/user/dashboard/followers', [DashboardController::class, 'followers'])->name('user.dashboard.followers');
    Route::get('/user/dashboard/profile', [DashboardController::class, 'profile'])->name('user.dashboard.profile'); // B - profile style
    Route::put('/user/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('user.dashboard.updateProfile');
    Route::post('/user/dashboard/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
    Route::delete('/user/dashboard/experiences/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');
    Route::post('/experiences/{experience}/view', [ExperienceController::class, 'view'])->name('experiences.view');
    Route::get('/user/dashboard/events', [DashboardController::class, 'events'])->name('user.dashboard.events'); // C - cards grid
    Route::get('/user/dashboard/trends', [DashboardController::class, 'trends'])->name('user.dashboard.trends'); // C - cards grid
    Route::get('/user/dashboard/security', [DashboardController::class, 'security'])->name('user.dashboard.security');
    Route::put('/user/dashboard/security', [DashboardController::class, 'updatePassword'])->name('user.dashboard.updatePassword');
    Route::get('/user/dashboard/support', [DashboardController::class, 'support'])->name('user.dashboard.support');
    Route::get('/user/dashboard/notifications', [DashboardController::class, 'notifications'])->name('user.dashboard.notifications');
    Route::post('/dashboard/notifications/{notification}/read', [DashboardController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/dashboard/notifications/read-all', [DashboardController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/user/dashboard/saved', [DashboardController::class, 'savedEvents'])->name('user.dashboard.saved');
    Route::get('/user/dashboard/calendar', [DashboardController::class, 'calendar'])->name('user.dashboard.calendar');

    // New Ticketing Features
    Route::get('/user/dashboard/tickets', [DashboardController::class, 'myTickets'])->name('user.dashboard.tickets');
    Route::get('/user/dashboard/orders', [DashboardController::class, 'myOrders'])->name('user.dashboard.orders');
    Route::get('user/dashboard/ticket/{ticket}', [DashboardController::class, 'viewTicket'])->name('dashboard.ticket.view');

    // Update Trend
    Route::put('/trends/{trend}', [TrendsController::class, 'update'])->name('trends.update');
    // Delete Trend
    Route::delete('/trends/{trend}', [TrendsController::class, 'destroy'])->name('trends.destroy');


    // Update Event
    Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
    // Delete Event
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');


    // Useful APIs used by the dashboard (like/follow/save)
    Route::post('/trend/{trend}/like', [DashboardController::class, 'toggleTrendLike'])->name('dashboard.trend.like');
    Route::post('/organizer/{organizer}/follow', [DashboardController::class, 'toggleOrganizerFollow'])->name('dashboard.organizer.follow');
    Route::post('/event/{event}/save', [DashboardController::class, 'toggleEventSave'])->name('dashboard.event.save');
    Route::post('/trend/share', [DashboardController::class, 'sharePost'])->name('dashboard.trend.share');
});

// 🏠 Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/site-reviews', [HomeController::class, 'store'])->middleware('auth')->name('site.reviews.store');


Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// 📧 Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// 🎤 Trends (public + protected create)
Route::get('/trends', [TrendsController::class, 'index'])->name('trends');
Route::post('/trends/{trend}/comment', [TrendsController::class, 'comment'])->middleware('auth')->name('trends.comment');
Route::get('/trend/{trend}/comments', [TrendsController::class, 'loadComments'])->name('trends.comments');
Route::get('/events/search', [TrendsController::class, 'search'])->name('events.search');

Route::post('/trends/comment/{comment}/like', [TrendsController::class, 'likeComment'])->name('trends.comment.like');
Route::delete('/trends/comment/{comment}', [TrendsController::class, 'deleteComment'])->name('trends.comment.delete');


Route::middleware('auth')->group(function () {
    Route::get('/trends/create', [TrendsController::class, 'create'])->name('trends.create');
    Route::post('/trends', [TrendsController::class, 'store'])->name('trends.store');
    Route::post('/trends/{trend}/like', [TrendsController::class, 'toggleLike'])->name('trends.like');
});

Route::get('/trends/{trend}', [TrendsController::class, 'show'])->name('trends.show');

// 🎫 Events
Route::get('/events', [EventsController::class, 'index'])->name('events');
Route::get('/categories/{category}', [EventsController::class, 'categoryPage'])->name('categories.show');
Route::get('/events/by-date', [EventsController::class, 'eventsByDate'])->name('byDate');
Route::get('/by-date', [EventsController::class, 'byDate'])->name('events.byDate');

// 🔐 Protected event actions
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventsController::class, 'create_event'])->name('events.create');
    Route::post('/events/store', [EventsController::class, 'store'])->name('events.store');
    Route::get('/events/saved', [SavedEventController::class, 'index'])->name('events.saved');
    Route::post('/events/{id}/like', [LikeController::class, 'toggle'])->name('events.like');
    Route::post('/events/{id}/save', [SavedEventController::class, 'toggle'])->name('events.save');
    Route::post('/events/{event}/comment', [EventsController::class, 'comment'])->name('events.comment');
    Route::post('/events/comment/{comment}/like', [EventsController::class, 'likeComment'])->name('events.comment.like');
    Route::delete('/events/comment/{comment}', [EventsController::class, 'deleteComment'])->name('events.comment.delete');

    // Organizer Tools
    Route::get('/dashboard/events/{event}/retargeting', [DashboardController::class, 'retargeting'])->name('organizer.retargeting');
    Route::post('/dashboard/events/{event}/send-offer', [DashboardController::class, 'sendOffer'])->name('organizer.sendOffer');
    Route::post('/dashboard/events/{event}/boost', [DashboardController::class, 'toggleBoost'])->name('organizer.boost');
    
    // Paid Boosting
    Route::get('/dashboard/events/{event}/boost/select', [BoostController::class, 'selectPlan'])->name('boost.select');
    Route::post('/dashboard/events/{event}/boost/init', [BoostController::class, 'initialize'])->name('boost.init');
    Route::get('/boost/callback', [BoostController::class, 'callback'])->name('boost.callback');
});

Route::get('/events/{id}', [EventsController::class, 'singleEvent'])->name('event.show');

// 👥 Organizers
Route::get('/organizers', [OrganizersController::class, 'index'])->name('organizers');
    // Organizer Analytics & Promotions
    Route::get('/organizer/analytics', [OrganizerAnalyticsController::class, 'index'])
        ->middleware('auth')
        ->name('organizer.analytics');
    Route::post('/organizer/promo/store', [OrganizerAnalyticsController::class, 'storePromo'])->name('organizer.promo.store');
    Route::post('/organizer/promo/{promoCode}/toggle', [OrganizerAnalyticsController::class, 'togglePromo'])->name('organizer.promo.toggle');

    // Payout Requests
    Route::get('/organizer/payouts', [PayoutController::class, 'index'])->name('organizer.payouts');
    Route::post('/organizer/payouts/request', [PayoutController::class, 'store'])->name('organizer.payouts.request');

    // Waitlist
    Route::post('/events/{event}/waitlist/join', [WaitlistController::class, 'join'])->name('waitlist.join');
    Route::post('/events/{event}/waitlist/leave', [WaitlistController::class, 'leave'])->name('waitlist.leave');

    Route::get('/organizer/create', [OrganizersController::class, 'organizer_create'])->name('organizer.create');

// 🔐 Protected Organizer actions
Route::middleware('auth')->group(function () {
    Route::post('/organizer/store', [OrganizersController::class, 'store'])->name('organizer_store');
    Route::get('/organizer/signup', [OrganizersController::class, 'organizer_signup'])->name('organizer.signup');
    Route::post('/organizer/tier/buy', [TierController::class, 'buyTier'])->name('organizer.tier.buy');
    Route::get('/organizer/tier/callback', [TierController::class, 'callback'])->name('organizer.tier.callback');
});

    // Event Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::post('/organizer/{organizer}/follow', [OrganizerFollowController::class, 'toggleFollow'])
        ->middleware('auth')
        ->name('organizer.follow');

Route::get('/organizer/{id}', [OrganizersController::class, 'show'])->name('organizer.details');

Route::get('/discover', [DiscoveryController::class, 'index'])->name('discover');

//User
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.profile');
Route::get('/u/{username}', [UserController::class, 'showByUsername'])->name('user.profile.username');
Route::post('/user/{user}/follow', [UserController::class, 'follow'])->name('user.follow');
Route::delete('/user/{user}/unfollow', [UserController::class, 'unfollow'])->name('user.unfollow');

Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // Payment Related
    Route::get('/payment/{event}', [PaymentController::class, 'paymentPage'])->name('payment.page');
    Route::get('/payment/fx-quote', [PaymentController::class, 'fxQuote'])->name('payment.fx-quote');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

    // MTN Mobile Money (MoMo) Integration
    Route::get('/momo/init', [MomoController::class, 'init'])->name('momo.init');
    Route::post('/momo/pay', [MomoController::class, 'pay'])->name('momo.pay');
    Route::post('/momo/callback', [MomoController::class, 'callback'])->name('momo.callback');
    Route::get('/momo/check/{purchase}', [MomoController::class, 'checkPayment']);

    // Flutterwave Integration
    Route::post('/flutterwave/pay', [FlutterwaveController::class, 'initialize'])->name('flutterwave.pay');
    Route::get('/flutterwave/callback', [FlutterwaveController::class, 'callback'])->name('flutterwave.callback');

    // Ticket Transfers
    Route::get('/ticket/transfer/accept/{token}', [TicketController::class, 'processTransfer'])->name('ticket.transfer.accept');
    Route::post('/ticket/transfer/confirm/{token}', [TicketController::class, 'confirmTransfer'])->name('ticket.transfer.confirm');
});

// 🔑 Authentication
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLogin')->name('show.login');
        Route::post('/login', 'login')->name('login');
        Route::get('/signup', 'showSignup')->name('show.signup');
        Route::post('/signup', 'signup')->name('signup');
    });

    // Google Login
    Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

    // Password Reset
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Email Verification Routes (Needs Auth, but Notice can be hit when already logged in)
Route::get('/verify-email', [VerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Ticket Viewing & Downloading


Route::get('/momo/token-test', function (App\Services\MtnService $mtn) {
    dd($mtn->getAccessToken());
});

// Ticket Viewing & Downloading
Route::get('/ticket/view/{purchase}', [TicketController::class, 'viewPurchase'])->name('ticket.view');
Route::get('/ticket/{code}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{code}/download', [TicketController::class, 'download'])->name('ticket.download');
Route::post('/ticket/{ticket}/transfer', [TicketController::class, 'transfer'])->name('ticket.transfer');
Route::post('/ticket/transfer/{transfer}/cancel', [TicketController::class, 'cancelTransfer'])->name('ticket.transfer.cancel');
