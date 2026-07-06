<?php

namespace Database\Seeders;

use App\Models\Quad;
use Illuminate\Database\Seeder;

class QuadSeeder extends Seeder
{
    public function run(): void
    {
        $quads = [
            [
                'name'      => 'Квадроцикл №1 — "Медведь"',
                'image'     => 'quads/quad-1.jpg',
                'is_active' => true,
            ],
            [
                'name'      => 'Квадроцикл №2 — "Волк"',
                'image'     => 'quads/quad-2.jpg',
                'is_active' => true,
            ],
            [
                'name'      => 'Квадроцикл №3 — "Орёл"',
                'image'     => 'quads/quad-3.jpg',
                'is_active' => true,
            ],
            [
                'name'      => 'Квадроцикл №4 — "Барс"',
                'image'     => 'quads/quad-4.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($quads as $quad) {
            Quad::updateOrCreate(['name' => $quad['name']], $quad);
        }
    }
}
