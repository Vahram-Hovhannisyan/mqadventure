<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingController extends Controller
{
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
}
