<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\GalleryItem;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'bookings_new'   => Booking::where('status', 'new')->count(),
            'bookings_total' => Booking::count(),
            'tours_active'   => Tour::where('is_active', true)->count(),
            'gallery_total'  => GalleryItem::count(),
        ];

        $recent = Booking::with('tour')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recent'));
    }
}
