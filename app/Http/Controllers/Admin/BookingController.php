<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Services\AvailabilityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    protected AvailabilityService $availability;

    public function __construct(AvailabilityService $availability)
    {
        $this->availability = $availability;
    }
    public function index(Request $request)
    {
        $query = Booking::with('tour')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(25)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', Booking::STATUSES)],
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Статус обновлён');
    }

    /**
     * Update the date/time/people/duration of an existing booking,
     * re-checking quad availability (excluding this booking itself).
     */
    public function updateSchedule(Request $request, Booking $booking, AvailabilityService $availability)
    {
        $data = $request->validate([
            'date'            => ['required', 'date'],
            'time'            => ['required', 'date_format:H:i'],
            'people'          => ['required', 'integer', 'min:1', 'max:20'],
            'duration_hours'  => ['nullable', 'numeric', 'min:0.5', 'max:24'],
        ]);

        if (!$booking->tour) {
            return back()->withErrors(['date' => 'У заявки не указан тур — сначала свяжите заявку с туром.']);
        }

        $tour = $booking->tour;

        if ($data['people'] > $tour->getMaxCapacity()) {
            return back()
                ->withErrors(['people' => "Максимум для этого тура: {$tour->getMaxCapacity()} чел."])
                ->withInput();
        }

        $durationHours = $data['duration_hours'] ?? null;

        $available = $availability->isAvailable(
            $tour,
            $data['date'],
            $data['time'],
            $data['people'],
            $durationHours,
            ignoreBookingId: $booking->id
        );

        if (!$available) {
            return back()
                ->withErrors(['time' => 'На это время недостаточно свободных квадроциклов. Проверьте другие заявки или снимите блокировку.'])
                ->withInput();
        }

        $resolvedDuration = $availability->resolveDurationHours($tour, $durationHours);
        $quadsUsed = $availability->requiredQuads($tour, $data['people']);

        $booking->update([
            'date'           => $data['date'],
            'time'           => $data['time'],
            'people'         => $data['people'],
            'duration_hours' => $durationHours ?: $resolvedDuration,
            'quads_used'     => $quadsUsed,
        ]);

        return back()->with('success', 'Расписание заявки обновлено');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.booking.index')->with('success', 'Заявка удалена');
    }

    public function exportPdf(Booking $booking)
    {
        $pdf = Pdf::loadView('admin.bookings.pdf', compact('booking'));

        return $pdf->download('booking-' . $booking->id . '.pdf');
    }

    // BookingController
    public function availableQuads(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'date'    => 'required|date',
            'time'    => 'required',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $start = Carbon::parse($request->date . ' ' . $request->time);
        $end   = $start->copy()->addMinutes($tour->duration_minutes);

        $quads = $this->availability->availableQuads($request->date, $start, $end);

        return response()->json($quads->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->name,
            'image_url' => $q->image_url,
        ]));
    }
}
