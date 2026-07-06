<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * GET /availability/slots?tour_id=&date=&people=
     * Returns the list of bookable time slots for the given tour/date/people count,
     * so the front-end form can show only what's actually available.
     */
    public function slots(Request $request, AvailabilityService $availability)
    {
        $data = $request->validate([
            'tour_id' => ['required', 'exists:tours,id'],
            'date'    => ['required', 'date'],
            'people'  => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $tour   = Tour::findOrFail($data['tour_id']);
        $people = $data['people'] ?? 1;

        if ($availability->isDateFullyBlocked($tour, $data['date'])) {
            return response()->json([
                'blocked'    => true,
                'max_people' => $tour->getMaxCapacity(),
                'slots'      => [],
            ]);
        }

        return response()->json([
            'blocked'    => false,
            'max_people' => $tour->getMaxCapacity(),
            'slots'      => $availability->getAvailableSlots($tour, $data['date'], $people),
        ]);
    }

    /**
     * GET /availability/quads?tour_id=&date=&time=
     * Returns the list of named quads still free for the chosen tour/date/time slot,
     * so the client can optionally pick specific machines.
     */
    public function quads(Request $request, AvailabilityService $availability)
    {
        $data = $request->validate([
            'tour_id' => ['required', 'exists:tours,id'],
            'date'    => ['required', 'date'],
            'time'    => ['required'],
        ]);

        $tour     = Tour::findOrFail($data['tour_id']);
        $duration = $availability->resolveDurationHours($tour);

        $start = Carbon::parse($data['date'] . ' ' . $data['time']);
        $end   = $start->copy()->addMinutes((int) round($duration * 60));

        $quads = $availability->availableQuads($data['date'], $start, $end);

        return response()->json($quads->map(fn ($q) => [
            'id'        => $q->id,
            'name'      => $q->name,
            'image_url' => $q->image_url,
        ]));
    }
}
