<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\Booking;
use App\Models\GalleryItem;
use App\Models\Tour;
use App\Services\AvailabilityService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(AvailabilityService $availability)
    {
        $stats = [
            'bookings_new'   => Booking::where('status', 'new')->count(),
            'bookings_total' => Booking::count(),
            'tours_active'   => Tour::where('is_active', true)->count(),
            'gallery_total'  => GalleryItem::count(),
        ];

        $today = Booking::with(['tour', 'quads'])   // было with('tour')
        ->whereDate('date', Carbon::today())
            ->whereNotIn('status', ['cancelled'])
            ->whereNotNull('time')
            ->orderBy('time')
            ->get();

        $recent = Booking::with(['tour', 'quads'])  // было with('tour')
        ->latest()
            ->limit(10)
            ->get();

        // ── Quad usage right now (across all active tours) ─────────
        // ── Quad usage right now (across all active tours) ─────────
        $now = Carbon::now();
        $activeTours = Tour::where('is_active', true)->get();

        $quadsUsage = [];
        $quadsUsedTotal = 0;

        foreach ($activeTours as $tour) {
            $used = $availability->usedQuads(
                $tour,
                $now->format('Y-m-d'),
                $now->copy(),
                $now->copy()->addMinute()
            );

            $quadsUsage[] = [
                'tour'  => $tour,
                'used'  => $used,
                'total' => $tour->quads_total,
            ];

            $quadsUsedTotal += $used;
        }

// реальный размер флота — берём один раз, а не суммируем по турам
        $quadsCapacityTotal = \App\Models\Quad::count();

        // ── Upcoming manual blocks (today onward) ───────────────────
        $upcomingBlocks = BlockedSlot::with('tour')
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent',
            'today',
            'quadsUsage',
            'quadsUsedTotal',
            'quadsCapacityTotal',
            'upcomingBlocks'
        ));
    }
}
