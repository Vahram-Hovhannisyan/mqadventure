<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\Booking;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $tours = Tour::orderBy('sort_order')->get();

        return view('admin.calendar.index', compact('tours'));
    }

    /**
     * JSON feed of bookings + blocked slots for FullCalendar.
     * GET /admin/calendar/events?tour_id=
     */
    public function events(Request $request)
    {
        $tourId = $request->get('tour_id');

        $bookingsQuery = Booking::with('tour')
            ->whereNotIn('status', ['cancelled'])
            ->whereNotNull('date');

        if ($tourId) {
            $bookingsQuery->where('tour_id', $tourId);
        }

        $bookingEvents = $bookingsQuery->get()->map(function (Booking $b) {
            $start = $b->date->format('Y-m-d') . ($b->time ? 'T' . substr($b->time, 0, 5) : '');
            $end   = null;
            $endDt = $b->getEndDateTime();
            if ($endDt) {
                $end = $endDt->format('Y-m-d\TH:i');
            }

            $tourName = $b->tour?->getName() ?? 'Тур';

            return [
                'id'      => 'booking-' . $b->id,
                'title'   => "{$tourName} — {$b->name} ({$b->people} чел / {$b->quads_used} кв)",
                'start'   => $start,
                'end'     => $end,
                'allDay'  => !$b->time,
                'color'   => match ($b->status) {
                    'new'       => '#f59e0b',
                    'confirmed' => '#16a34a',
                    'completed' => '#6b7280',
                    default     => '#94a3b8',
                },
                'url'     => route('admin.booking.show', $b->id),
                'extendedProps' => [
                    'type' => 'booking',
                ],
            ];
        });

        $blockedQuery = BlockedSlot::query();
        if ($tourId) {
            $blockedQuery->forTour((int) $tourId);
        }

        $blockedEvents = $blockedQuery->get()->map(function (BlockedSlot $s) {
            $start = $s->date->format('Y-m-d') . ($s->start_time ? 'T' . substr($s->start_time, 0, 5) : '');
            $end   = $s->end_time ? $s->date->format('Y-m-d') . 'T' . substr($s->end_time, 0, 5) : null;

            return [
                'id'      => 'blocked-' . $s->id,
                'title'   => '🚫 ' . ($s->reason ?: 'Закрыто') . (!$s->tour_id ? ' (все туры)' : ''),
                'start'   => $start,
                'end'     => $end,
                'allDay'  => $s->isFullDay(),
                'color'   => '#ef4444',
                'display' => 'block',
                'extendedProps' => [
                    'type' => 'blocked',
                    'blocked_id' => $s->id,
                ],
            ];
        });

        return response()->json($bookingEvents->concat($blockedEvents)->values());
    }

    /**
     * Store a manual block (whole day or a time range), global or tour-specific.
     */
    public function storeBlock(Request $request)
    {
        $data = $request->validate([
            'tour_id'    => ['nullable', 'exists:tours,id'],
            'date'       => ['required', 'date'],
            'full_day'   => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_if:full_day,false'],
            'end_time'   => ['nullable', 'date_format:H:i', 'required_if:full_day,false', 'after:start_time'],
            'reason'     => ['nullable', 'string', 'max:255'],
        ]);

        $fullDay = $request->boolean('full_day');

        BlockedSlot::create([
            'tour_id'    => $data['tour_id'] ?? null,
            'date'       => $data['date'],
            'start_time' => $fullDay ? null : $data['start_time'],
            'end_time'   => $fullDay ? null : $data['end_time'],
            'reason'     => $data['reason'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Время закрыто для бронирования');
    }

    public function destroyBlock(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();

        return back()->with('success', 'Блокировка снята');
    }
}
