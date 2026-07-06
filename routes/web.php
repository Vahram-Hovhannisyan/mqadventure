<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\QuadController;
use App\Http\Controllers\Front;
use App\Http\Controllers\Front\AvailabilityController;
use Illuminate\Support\Facades\Route;

// ── Language switch ───────────────────────────────────────────────────────────
Route::get('/lang/{locale}', [Front\LocaleController::class, 'switch'])->name('locale.switch');
Route::get('admin/lang/{locale}', function ($locale) {
    if (in_array($locale, ['hy', 'ru', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('admin.lang')->middleware('auth:admin');
// ── Front ─────────────────────────────────────────────────────────────────────

Route::get('/', [Front\HomeController::class, 'index'])->name('home');
Route::post('/booking', [Front\BookingController::class, 'store'])->name('booking.store');

// ── Admin auth ────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login']);
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
});

// ── Admin panel ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Tours
    Route::resource('tours', Admin\TourController::class);

    // Bookings
    Route::get('/booking', [Admin\BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [Admin\BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/status', [Admin\BookingController::class, 'updateStatus'])->name('booking.updateStatus');
    Route::delete('/booking/{booking}', [Admin\BookingController::class, 'destroy'])->name('booking.destroy');

    // Gallery
    Route::get('/gallery', [Admin\GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [Admin\GalleryController::class, 'store'])->name('gallery.store');
    Route::post('/gallery/order', [Admin\GalleryController::class, 'updateOrder'])->name('gallery.order');
    Route::delete('/gallery/{galleryItem}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

    // Page content / settings
    Route::get('/pages', [Admin\PageController::class, 'index'])->name('pages.index');
    Route::post('/pages', [Admin\PageController::class, 'update'])->name('pages.update');

    Route::get('bookings/{booking}/pdf', [BookingController::class, 'exportPdf'])
        ->name('booking.pdf');

    Route::patch('/booking/{booking}/schedule', [\App\Http\Controllers\Admin\BookingController::class, 'updateSchedule'])
        ->name('booking.updateSchedule');
    Route::get('/booking/available-quads', [BookingController::class, 'availableQuads']);

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::post('/calendar/block', [CalendarController::class, 'storeBlock'])->name('calendar.block.store');
    Route::delete('/calendar/block/{blockedSlot}', [CalendarController::class, 'destroyBlock'])->name('calendar.block.destroy');

    // routes/web.php (admin group)
    Route::resource('quads', QuadController::class)->names('quads');
});
Route::get('/availability/quads', [AvailabilityController::class, 'quads'])->name('availability.quads');
Route::get('/availability/slots', [AvailabilityController::class, 'slots'])
    ->name('availability.slots');
Route::get('/tours', [Front\TourController::class, 'page'])->name('tours.page');


