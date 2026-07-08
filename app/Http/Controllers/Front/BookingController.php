<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\NewBookingMail;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Models\Tour;
use App\Notifications\NewBookingNotification;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    protected AvailabilityService $availability;

    public function __construct(AvailabilityService $availability)
    {
        $this->availability = $availability;
    }
    public function store(Request $request, AvailabilityService $availability)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'phone'   => ['required', 'string', 'max:30'],
            'tour_id' => ['required', 'exists:tours,id'],
            'date'    => ['required', 'date', 'after_or_equal:today'],
            'time'    => ['required', 'date_format:H:i'],
            'people' => 'required|integer|min:1',
            'quads'  => 'nullable|array',
            'quads.*' => 'exists:quads,id',
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!empty($validated['quads']) && count($validated['quads']) > $validated['people']) {
            return back()->withErrors(['quads' => 'Нельзя выбрать квадроциклов больше, чем людей в заявке.']);
        }

        $tour = Tour::findOrFail($validated['tour_id']);

        // Hard cap: can't ever fit more people than the tour's total quad capacity allows.
        if ($validated['people'] > $tour->getMaxCapacity()) {
            return back()
                ->withErrors(['people' => __('front.booking_too_many_people')])
                ->withInput();
        }

        if (!$availability->isAvailable($tour, $validated['date'], $validated['time'], $validated['people'])) {
            return back()
                ->withErrors(['time' => __('front.booking_slot_unavailable')])
                ->withInput();
        }

        $duration  = $availability->resolveDurationHours($tour);
        $quadsUsed = $availability->requiredQuads($tour, $validated['people']);

        $booking = Booking::create([
            ...$validated,
            'duration_hours' => $duration,
            'quads_used'     => $quadsUsed,
            'status'         => 'new',
            'locale'         => app()->getLocale(),
            'ip'             => $request->ip(),
        ]);

        $adminEmails = config('mail.admin_notifications');
        if (!empty($adminEmails)) {
            Mail::to("vrm99ov@gmail.com")->send(new NewBookingMail($booking));
        }

        if (!empty($validated['quads'])) {
            // повторно проверяем доступность на случай гонки
            $start = $booking->getStartDateTime();
            $end   = $booking->getEndDateTime();
            $free = $this->availability->availableQuads($booking->date, $start, $end, $booking->id)
                ->pluck('id')->toArray();
            $booking->quads()->sync(array_intersect($validated['quads'], $free));
        }

        // Email notification to admin
        $adminEmail = SiteSetting::get('admin_email', null, config('mail.from.address'));
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewBookingNotification($booking));
        }

        return back()->with('success', __('front.booking_success_title'));
    }
}
