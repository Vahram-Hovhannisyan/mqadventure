<?php

namespace Database\Seeders;

use App\Models\BlockedSlot;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class BlockedSlotSeeder extends Seeder
{
    public function run(): void
    {
        $sevan = Tour::where('slug', 'lake-sevan-tour')->first();

        // Full-day block for ALL tours (e.g. a public holiday) — 10 days from now
        BlockedSlot::updateOrCreate(
            ['tour_id' => null, 'date' => now()->addDays(10)->format('Y-m-d'), 'start_time' => null, 'end_time' => null],
            ['reason' => 'Праздничный день — компания не работает']
        );

        // Partial time block for a specific tour (e.g. scheduled maintenance) — 3 days from now, 12:00–15:00
        if ($sevan) {
            BlockedSlot::updateOrCreate(
                [
                    'tour_id'    => $sevan->id,
                    'date'       => now()->addDays(3)->format('Y-m-d'),
                    'start_time' => '12:00',
                    'end_time'   => '15:00',
                ],
                ['reason' => 'Техобслуживание квадроциклов']
            );
        }
    }
}
