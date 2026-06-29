<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        Tour::truncate();

        // ── 1. Классический тур ───────────────────────────────────────────
        Tour::create([
            'slug'         => 'classic',
            'badge_color'  => 'orange',
            'duration_min' => 2,
            'duration_max' => 3,
            'people_min'   => 1,
            'people_max'   => 6,
            'price_from'   => 8000,
            'is_active'    => true,
            'sort_order'   => 1,
            'image'        => null,
            'name' => [
                'hy' => 'Դասական շրջայց',
                'ru' => 'Классический тур',
                'en' => 'Classic Tour',
            ],
            'badge' => [
                'hy' => 'Հայտնի',
                'ru' => 'Популярный',
                'en' => 'Popular',
            ],
            'description' => [
                'hy' => 'Մեղրաձորի հիմնական երթուղին՝ տեսարժան վայրերով և բնական լանդшафтներով։',
                'ru' => 'Основной маршрут по Мегри с панорамными видами и природными ландшафтами.',
                'en' => 'The main Meghri route with panoramic views and natural landscapes.',
            ],
            'route_points' => [
                [
                    'lat'   => 38.9020,
                    'lng'   => 46.2350,
                    'label' => [
                        'hy' => 'Մեկնարկ — Բազա',
                        'ru' => 'Старт — База',
                        'en' => 'Start — Base',
                    ],
                    'media' => [],
                ],
                [
                    'lat'   => 38.9145,
                    'lng'   => 46.2480,
                    'label' => [
                        'hy' => 'Դիտման հարթակ',
                        'ru' => 'Смотровая площадка',
                        'en' => 'Viewpoint',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/view1.jpg'],
                        ['type' => 'image', 'path' => 'tours/demo/view2.jpg'],
                    ],
                ],
                [
                    'lat'   => 38.9230,
                    'lng'   => 46.2610,
                    'label' => [
                        'hy' => 'Ձորի կամուրջ',
                        'ru' => 'Мост через ущелье',
                        'en' => 'Canyon Bridge',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/bridge1.jpg'],
                    ],
                ],
                [
                    'lat'   => 38.9310,
                    'lng'   => 46.2720,
                    'label' => [
                        'hy' => 'Ավարտ — Բազա',
                        'ru' => 'Финиш — База',
                        'en' => 'Finish — Base',
                    ],
                    'media' => [],
                ],
            ],
        ]);

        // ── 2. Горный тур ─────────────────────────────────────────────────
        Tour::create([
            'slug'         => 'mountain',
            'badge_color'  => 'green',
            'duration_min' => 4,
            'duration_max' => 5,
            'people_min'   => 2,
            'people_max'   => 8,
            'price_from'   => 14000,
            'is_active'    => true,
            'sort_order'   => 2,
            'image'        => null,
            'name' => [
                'hy' => 'Լեռնային արկած',
                'ru' => 'Горное приключение',
                'en' => 'Mountain Adventure',
            ],
            'badge' => [
                'hy' => 'Բեսթսելլեր',
                'ru' => 'Хит',
                'en' => 'Bestseller',
            ],
            'description' => [
                'hy' => 'Բարձր լեռնային երթուղի՝ Զանգեզուրի լեռնաշղթայի գագաթներով։',
                'ru' => 'Высокогорный маршрут по хребтам Зангезура с подъёмом до 2200 м.',
                'en' => 'High-altitude route along the Zangezur ridge, ascending to 2200 m.',
            ],
            'route_points' => [
                [
                    'lat'   => 38.9020,
                    'lng'   => 46.2350,
                    'label' => [
                        'hy' => 'Մեկնարկ — Բազա',
                        'ru' => 'Старт — База',
                        'en' => 'Start — Base',
                    ],
                    'media' => [],
                ],
                [
                    'lat'   => 38.8890,
                    'lng'   => 46.2180,
                    'label' => [
                        'hy' => 'Շիկահող արգելոց',
                        'ru' => 'Заповедник Шикахог',
                        'en' => 'Shikahogh Reserve',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/forest1.jpg'],
                        ['type' => 'image', 'path' => 'tours/demo/forest2.jpg'],
                        ['type' => 'video', 'path' => 'tours/demo/forest.mp4'],
                    ],
                ],
                [
                    'lat'   => 38.8750,
                    'lng'   => 46.2050,
                    'label' => [
                        'hy' => 'Ջրվեժ',
                        'ru' => 'Водопад',
                        'en' => 'Waterfall',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/waterfall1.jpg'],
                        ['type' => 'image', 'path' => 'tours/demo/waterfall2.jpg'],
                    ],
                ],
                [
                    'lat'   => 38.8620,
                    'lng'   => 46.1940,
                    'label' => [
                        'hy' => 'Լեռան գագաթ 2200մ',
                        'ru' => 'Вершина 2200 м',
                        'en' => 'Summit 2200 m',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/summit1.jpg'],
                    ],
                ],
                [
                    'lat'   => 38.9020,
                    'lng'   => 46.2350,
                    'label' => [
                        'hy' => 'Վերադարձ — Բազա',
                        'ru' => 'Возврат — База',
                        'en' => 'Return — Base',
                    ],
                    'media' => [],
                ],
            ],
        ]);

        // ── 3. Ночной тур ─────────────────────────────────────────────────
        Tour::create([
            'slug'         => 'night',
            'badge_color'  => 'orange',
            'duration_min' => 3,
            'duration_max' => 4,
            'people_min'   => 2,
            'people_max'   => 10,
            'price_from'   => 12000,
            'is_active'    => true,
            'sort_order'   => 3,
            'image'        => null,
            'name' => [
                'hy' => 'Գիշերային արշավ',
                'ru' => 'Ночной рейд',
                'en' => 'Night Raid',
            ],
            'badge' => [
                'hy' => 'Նոր',
                'ru' => 'Новинка',
                'en' => 'New',
            ],
            'description' => [
                'hy' => 'Անմոռանալի գիշերային արկած՝ լուսնի լույսի ներքո Մեղրաձորի ճանապարհներով։',
                'ru' => 'Незабываемое ночное приключение по трассам Мегри под звёздным небом.',
                'en' => 'Unforgettable night adventure through Meghri trails under the starry sky.',
            ],
            'route_points' => [
                [
                    'lat'   => 38.9020,
                    'lng'   => 46.2350,
                    'label' => [
                        'hy' => 'Մեկնարկ — Բազա',
                        'ru' => 'Старт — База',
                        'en' => 'Start — Base',
                    ],
                    'media' => [],
                ],
                [
                    'lat'   => 38.9180,
                    'lng'   => 46.2550,
                    'label' => [
                        'hy' => 'Լուսնային հովիտ',
                        'ru' => 'Лунная долина',
                        'en' => 'Moon Valley',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/night1.jpg'],
                        ['type' => 'image', 'path' => 'tours/demo/night2.jpg'],
                    ],
                ],
                [
                    'lat'   => 38.9280,
                    'lng'   => 46.2650,
                    'label' => [
                        'hy' => 'Կրակի վայր',
                        'ru' => 'Место костра',
                        'en' => 'Bonfire Spot',
                    ],
                    'media' => [
                        ['type' => 'image', 'path' => 'tours/demo/bonfire1.jpg'],
                        ['type' => 'video', 'path' => 'tours/demo/bonfire.mp4'],
                    ],
                ],
                [
                    'lat'   => 38.9020,
                    'lng'   => 46.2350,
                    'label' => [
                        'hy' => 'Ավարտ — Բազա',
                        'ru' => 'Финиш — База',
                        'en' => 'Finish — Base',
                    ],
                    'media' => [],
                ],
            ],
        ]);
    }
}
