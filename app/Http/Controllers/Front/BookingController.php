<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Notifications\NewBookingNotification;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'phone'   => ['required', 'string', 'max:30'],
            'tour_id' => ['nullable', 'exists:tours,id'],
            'date'    => ['nullable', 'date', 'after_or_equal:today'],
            'people'  => ['nullable', 'integer', 'min:1', 'max:20'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking = Booking::create([
            ...$validated,
            'status' => 'new',
            'locale' => app()->getLocale(),
            'ip'     => $request->ip(),
        ]);

        // Email notification to admin
        $adminEmail = SiteSetting::get('admin_email', null, config('mail.from.address'));
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewBookingNotification($booking));
        }

        return back()->with('success', __('front.booking_success'));
    }
}
