<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    ContactController,
    EventsController,
    LikeController,
    NewsletterController,
    OrganizersController,
    SavedEventController,
    TrendsController,
    HomeController,
    OrganizerFollowController,
    MomoController,
    PaymentController,
    TicketController,
    UserController,
    DashboardController
};

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'overview'])->name('user.dashboard.overview'); // A - sidebar
    Route::get('/user/dashboard/profile', [DashboardController::class, 'profile'])->name('user.dashboard.profile'); // B - profile style
    Route::put('/user/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('user.dashboard.updateProfile');
    Route::get('/user/dashboard/events', [DashboardController::class, 'events'])->name('user.dashboard.events'); // C - cards grid
    Route::get('/user/dashboard/trends', [DashboardController::class, 'trends'])->name('user.dashboard.trends'); // C - cards grid

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
});

// 🏠 Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/reviews', [HomeController::class, 'store'])->middleware('auth')->name('reviews.store');


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
Route::get('/events/by-date', [EventsController::class, 'eventsByDate'])->name('byDate');
Route::get('/by-date', [EventsController::class, 'byDate'])->name('events.byDate');

// 🔐 Protected event actions
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventsController::class, 'create_event'])->name('events.create');
    Route::post('/events/store', [EventsController::class, 'store'])->name('events.store');
    Route::get('/events/saved', [SavedEventController::class, 'index'])->name('events.saved');
    Route::post('/events/{id}/like', [LikeController::class, 'toggle'])->name('events.like');
    Route::post('/events/{id}/save', [SavedEventController::class, 'toggle'])->name('events.save');
});

Route::get('/events/{id}', [EventsController::class, 'singleEvent'])->name('event.show');

// 👥 Organizers
Route::get('/organizers', [OrganizersController::class, 'index'])->name('organizers');
Route::get('/organizer/create', [OrganizersController::class, 'organizer_create'])->name('organizer.create');

// 🔐 Protected Organizer actions
Route::middleware('auth')->group(function () {
    Route::post('/organizer/store', [OrganizersController::class, 'store'])->name('organizer_store');
    Route::get('/organizer/signup', [OrganizersController::class, 'organizer_signup'])->name('organizer.signup');
});

Route::post('/organizer/{organizer}/follow', [OrganizerFollowController::class, 'toggleFollow'])
    ->middleware('auth')
    ->name('organizer.follow');

Route::get('/organizer/{id}', [OrganizersController::class, 'show'])->name('organizer.details');

//User
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.profile');
Route::post('/user/{user}/follow', [UserController::class, 'follow'])->name('user.follow');
Route::delete('/user/{user}/unfollow', [UserController::class, 'unfollow'])->name('user.unfollow');

// 🔑 Authentication
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/login', 'login')->name('login');
    Route::get('/signup', 'showSignup')->name('show.signup');
    Route::post('/signup', 'signup')->name('signup');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Payment Page
Route::get('/payment/{event}', [PaymentController::class, 'paymentPage'])->name('payment.page');
// Form submission handler
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');


// MTN Mobile Money (MoMo) Integration
Route::get('/momo/init', [MomoController::class, 'init'])->name('momo.init'); // Loads summary page
Route::post('/momo/pay', [MomoController::class, 'pay'])->name('momo.pay');   // Calls MTN API
Route::post('/momo/callback', [MomoController::class, 'callback'])->name('momo.callback'); // Receives webhook
Route::get('/momo/check/{purchase}', [MomoController::class, 'checkPayment']);
Route::get('/momo/token-test', function (App\Services\MtnService $mtn) {
    dd($mtn->getAccessToken());
});

// Ticket Viewing & Downloading
Route::get('/ticket/{code}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{code}/download', [TicketController::class, 'download'])->name('ticket.download');




