<?php

namespace App\Services;

use App\Models\BlockedSlot;
use App\Models\Booking;
use App\Models\Quad;
use App\Models\Tour;
use Carbon\Carbon;

class AvailabilityService
{
    public function openTime(): string
    {
        return config('booking.open_time', '08:00');
    }

    public function closeTime(): string
    {
        return config('booking.close_time', '20:00');
    }

    public function slotIntervalMinutes(): int
    {
        return (int) config('booking.slot_interval', 30);
    }

    public function requiredQuads(Tour $tour, int $people): int
    {
        return $tour->getRequiredQuads($people);
    }

    /**
     * Resolves the duration (in hours) to use for blocking the schedule.
     * Flexible by design: an explicit value (e.g. an admin override, or a
     * booking's own stored duration) always wins; otherwise falls back to
     * the tour's duration_max (safe/conservative default), or duration_min
     * if duration_max isn't set.
     */
    public function resolveDurationHours(Tour $tour, ?float $requested = null): float
    {
        if ($requested && $requested > 0) {
            return $requested;
        }

        return (float) ($tour->duration_max ?: $tour->duration_min ?: 4);
    }

    /**
     * True if the whole day is blocked for this tour (globally or specifically).
     */
    public function isDateFullyBlocked(Tour $tour, string $date): bool
    {
        return BlockedSlot::query()
            ->forTour($tour->id)
            ->where('date', $date)
            ->whereNull('start_time')
            ->whereNull('end_time')
            ->exists();
    }

    /**
     * How many quads are already committed for this tour during [start, end)
     * on the given date, optionally excluding one booking (useful on edit).
     */
    public function usedQuads(Tour $tour, string $date, Carbon $start, Carbon $end, ?int $ignoreBookingId = null): int
    {
        $bookings = Booking::where('tour_id', $tour->id)
            ->whereDate('date', $date)
            ->activeStatus()
            ->whereNotNull('time')
            ->when($ignoreBookingId, fn ($q) => $q->where('id', '!=', $ignoreBookingId))
            ->get();

        $used = 0;

        foreach ($bookings as $booking) {
            $bStart = $booking->getStartDateTime();
            $bEnd   = $booking->getEndDateTime();

            if (!$bStart || !$bEnd) {
                continue;
            }

            // Overlap check: two ranges overlap if one starts before the other ends.
            if ($bStart->lt($end) && $bEnd->gt($start)) {
                $used += $booking->quads_used ?: $this->requiredQuads($tour, $booking->people);
            }
        }

        return $used;
    }

    /**
     * Checks whether any manually-blocked time range overlaps [start, end)
     * for this tour on this date (global or tour-specific blocks).
     */
    public function hasBlockingOverlap(Tour $tour, string $date, Carbon $start, Carbon $end): bool
    {
        $blocked = BlockedSlot::query()
            ->forTour($tour->id)
            ->where('date', $date)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        foreach ($blocked as $slot) {
            $bStart = Carbon::parse($date . ' ' . $slot->start_time);
            $bEnd   = Carbon::parse($date . ' ' . $slot->end_time);

            if ($bStart->lt($end) && $bEnd->gt($start)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Main availability check used both by the booking form and by the
     * admin when manually creating/editing a booking.
     */
    public function isAvailable(
        Tour $tour,
        string $date,
        string $time,
        int $people,
        ?float $durationHours = null,
        ?int $ignoreBookingId = null
    ): bool {
        if ($this->isDateFullyBlocked($tour, $date)) {
            return false;
        }

        $duration = $this->resolveDurationHours($tour, $durationHours);
        $start = Carbon::parse($date . ' ' . $time);
        $end   = $start->copy()->addMinutes((int) round($duration * 60));

        if ($this->hasBlockingOverlap($tour, $date, $start, $end)) {
            return false;
        }

        $required = $this->requiredQuads($tour, $people);
        $used = $this->usedQuads($tour, $date, $start, $end, $ignoreBookingId);

        return ($used + $required) <= $tour->quads_total;
    }

    /**
     * Generates the list of bookable time slots for a given tour/date/people,
     * stepping every slotIntervalMinutes between business open/close hours.
     * Each slot only requires the tour's own duration to fit before closing.
     */
    public function getAvailableSlots(Tour $tour, string $date, int $people): array
    {
        if ($this->isDateFullyBlocked($tour, $date)) {
            return [];
        }

        $duration = $this->resolveDurationHours($tour);
        $durationMinutes = (int) round($duration * 60);
        $interval = $this->slotIntervalMinutes();

        $open  = Carbon::parse($date . ' ' . $this->openTime());
        $close = Carbon::parse($date . ' ' . $this->closeTime());

        $required = $this->requiredQuads($tour, $people);

        $slots = [];
        $cursor = $open->copy();

        while ($cursor->copy()->addMinutes($durationMinutes)->lte($close)) {
            $start = $cursor->copy();
            $end   = $cursor->copy()->addMinutes($durationMinutes);

            $blockedHere = $this->hasBlockingOverlap($tour, $date, $start, $end);
            $used = $blockedHere ? $tour->quads_total : $this->usedQuads($tour, $date, $start, $end);
            $free = max(0, $tour->quads_total - $used);

            $slots[] = [
                'time'       => $cursor->format('H:i'),
                'available'  => !$blockedHere && ($free >= $required),
                'free_quads' => $free,
            ];

            $cursor->addMinutes($interval);
        }

        return $slots;
    }

    // в App\Services\AvailabilityService

    /**
     * Which named quads are still free for [start, end) on the given date,
     * across ALL tours (shared fleet), optionally excluding one booking (edit case).
     */
    public function availableQuads(string $date, Carbon $start, Carbon $end, ?int $ignoreBookingId = null): \Illuminate\Support\Collection
    {
        $bookings = Booking::with('quads:id')
            ->whereDate('date', $date)
            ->activeStatus()
            ->whereNotNull('time')
            ->when($ignoreBookingId, fn ($q) => $q->where('id', '!=', $ignoreBookingId))
            ->get();

        $busyQuadIds = collect();

        foreach ($bookings as $booking) {
            $bStart = $booking->getStartDateTime();
            $bEnd   = $booking->getEndDateTime();

            if (!$bStart || !$bEnd) {
                continue;
            }

            if ($bStart->lt($end) && $bEnd->gt($start)) {
                $busyQuadIds = $busyQuadIds->merge($booking->quads->pluck('id'));
            }
        }

        return Quad::active()
            ->whereNotIn('id', $busyQuadIds->unique())
            ->orderBy('name')
            ->get();
    }}
