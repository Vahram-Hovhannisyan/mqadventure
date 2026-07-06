<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'name' => [
                    'hy' => 'Սևանա լճի շրջագայություն',
                    'ru' => 'Тур на озеро Севан',
                    'en' => 'Lake Sevan Tour',
                ],
                'badge' => [
                    'hy' => 'Հանրաճանաչ',
                    'ru' => 'Популярный',
                    'en' => 'Popular',
                ],
                'badge_color' => '#2563eb',
                'description' => [
                    'hy' => 'Այցելություն Հայաստանի ամենամեծ լիճ՝ Սևանա լիճ, հայտնի իր բյուրեղյա ջրով և հին վանքերով։',
                    'ru' => 'Посещение крупнейшего озера Армении — Севан, известного своей кристально чистой водой и древними монастырями.',
                    'en' => 'Visit Armenia\'s largest lake, Sevan, known for its crystal-clear water and ancient monasteries.',
                ],
                'duration_min' => 6,
                'duration_max' => 8,
                'people_min' => 1,
                'people_max' => 15,
                'price_from' => 25000,
                'is_active' => true,
                'sort_order' => 1,
                'image' => 'tours/sevan.jpg',
                'quads_total' => 4,
                'seats_per_quad' => 2, // клиент сам водит, 2 человека на квадроцикл
                'route_points' => [
                    [
                        'lat' => 40.5566,
                        'lng' => 44.9425,
                        'label' => [
                            'hy' => 'Սևանավանք',
                            'ru' => 'Севанаванк',
                            'en' => 'Sevanavank Monastery',
                        ],
                        'media' => [
                            ['type' => 'image', 'path' => 'tours/sevan/point1.jpg'],
                        ],
                    ],
                    [
                        'lat' => 40.6111,
                        'lng' => 45.2075,
                        'label' => [
                            'hy' => 'Ծովագյուղ',
                            'ru' => 'Цовагюх',
                            'en' => 'Tsovagyugh',
                        ],
                        'media' => [],
                    ],
                ],
            ],
            [
                'name' => [
                    'hy' => 'Գառնի-Գեղարդ',
                    'ru' => 'Гарни-Гегард',
                    'en' => 'Garni-Geghard',
                ],
                'badge' => [
                    'hy' => 'Պատմական',
                    'ru' => 'Исторический',
                    'en' => 'Historical',
                ],
                'badge_color' => '#dc2626',
                'description' => [
                    'hy' => 'Հելլենիստական տաճար Գառնիում և ժայռափոր վանական համալիր Գեղարդում՝ ՅՈՒՆԵՍԿՕ-ի ժառանգության ցանկում։',
                    'ru' => 'Эллинистический храм в Гарни и высеченный в скале монастырь Гегард, объект всемирного наследия ЮНЕСКО.',
                    'en' => 'The Hellenistic temple at Garni and the rock-hewn Geghard Monastery, a UNESCO World Heritage Site.',
                ],
                'duration_min' => 5,
                'duration_max' => 6,
                'people_min' => 1,
                'people_max' => 12,
                'price_from' => 20000,
                'is_active' => true,
                'sort_order' => 2,
                'image' => 'tours/garni-geghard.jpg',
                'quads_total' => 4,
                'seats_per_quad' => 2,
                'route_points' => [
                    [
                        'lat' => 40.1103,
                        'lng' => 44.7300,
                        'label' => [
                            'hy' => 'Գառնիի տաճար',
                            'ru' => 'Храм Гарни',
                            'en' => 'Garni Temple',
                        ],
                        'media' => [
                            ['type' => 'image', 'path' => 'tours/garni/point1.jpg'],
                        ],
                    ],
                    [
                        'lat' => 40.1394,
                        'lng' => 44.8181,
                        'label' => [
                            'hy' => 'Գեղարդավանք',
                            'ru' => 'Гегардаванк',
                            'en' => 'Geghard Monastery',
                        ],
                        'media' => [
                            ['type' => 'video', 'path' => 'tours/garni/point2.mp4'],
                        ],
                    ],
                ],
            ],
            [
                'name' => [
                    'hy' => 'Տաթև - ամենաերկար ճոպանուղին',
                    'ru' => 'Татев - самая длинная канатная дорога',
                    'en' => 'Tatev - Longest Cable Car',
                ],
                'badge' => [
                    'hy' => 'Արկածային',
                    'ru' => 'Приключение',
                    'en' => 'Adventure',
                ],
                'badge_color' => '#16a34a',
                'description' => [
                    'hy' => 'Ուղևորություն դեպի Տաթևի վանք՝ աշխարհի ամենաերկար հետադարձ ճոպանուղով «Թևանք»։',
                    'ru' => 'Поездка к монастырю Татев на самой длинной в мире реверсивной канатной дороге «Крылья Татева».',
                    'en' => 'A journey to Tatev Monastery via the world\'s longest reversible cable car, the "Wings of Tatev".',
                ],
                'duration_min' => 10,
                'duration_max' => 12,
                'people_min' => 2,
                'people_max' => 20,
                'price_from' => 35000,
                'is_active' => true,
                'sort_order' => 3,
                'image' => 'tours/tatev.jpg',
                'quads_total' => 6,
                'seats_per_quad' => 2,
                'route_points' => [
                    [
                        'lat' => 39.3797,
                        'lng' => 46.2506,
                        'label' => [
                            'hy' => 'Տաթևի վանք',
                            'ru' => 'Монастырь Татев',
                            'en' => 'Tatev Monastery',
                        ],
                        'media' => [
                            ['type' => 'image', 'path' => 'tours/tatev/point1.jpg'],
                        ],
                    ],
                ],
            ],
            [
                'name' => [
                    'hy' => 'Դիլիջանի անտառներ',
                    'ru' => 'Леса Дилижана',
                    'en' => 'Dilijan Forests',
                ],
                'badge' => [
                    'hy' => 'Բնություն',
                    'ru' => 'Природа',
                    'en' => 'Nature',
                ],
                'badge_color' => '#0891b2',
                'description' => [
                    'hy' => 'Զբոսանք Դիլիջանի ազգային պարկի խիտ անտառներով և գյուղական տնակներով։',
                    'ru' => 'Прогулка по густым лесам национального парка Дилижан и его деревенским домикам.',
                    'en' => 'A walk through the dense forests of Dilijan National Park and its rustic villages.',
                ],
                'duration_min' => 4,
                'duration_max' => 5,
                'people_min' => 1,
                'people_max' => 10,
                'price_from' => 15000,
                'is_active' => true,
                'sort_order' => 4,
                'image' => 'tours/dilijan.jpg',
                'quads_total' => 3,
                'seats_per_quad' => 1, // маршрут сложный, водит только сопровождающий
                'route_points' => [
                    [
                        'lat' => 40.7431,
                        'lng' => 44.8650,
                        'label' => [
                            'hy' => 'Դիլիջան',
                            'ru' => 'Дилижан',
                            'en' => 'Dilijan',
                        ],
                        'media' => [],
                    ],
                ],
            ],
            [
                'name' => [
                    'hy' => 'Արարատյան դաշտ և Խոր Վիրապ',
                    'ru' => 'Араратская долина и Хор Вирап',
                    'en' => 'Ararat Valley and Khor Virap',
                ],
                'badge' => [
                    'hy' => 'Ընտանեկան',
                    'ru' => 'Семейный',
                    'en' => 'Family',
                ],
                'badge_color' => '#ea580c',
                'description' => [
                    'hy' => 'Այցելություն Խոր Վիրապ վանք՝ Արարատ լեռան հիասքանչ տեսարանով և գինու ֆերմայի համտեսով։',
                    'ru' => 'Посещение монастыря Хор Вирап с потрясающим видом на гору Арарат и дегустацией на винодельне.',
                    'en' => 'Visit Khor Virap Monastery with stunning views of Mount Ararat and a winery tasting.',
                ],
                'duration_min' => 4,
                'duration_max' => 6,
                'people_min' => 1,
                'people_max' => 15,
                'price_from' => 18000,
                'is_active' => false,
                'sort_order' => 5,
                'image' => 'tours/khor-virap.jpg',
                'quads_total' => 4,
                'seats_per_quad' => 2,
                'route_points' => [
                    [
                        'lat' => 39.8783,
                        'lng' => 44.5433,
                        'label' => [
                            'hy' => 'Խոր Վիրապ',
                            'ru' => 'Хор Вирап',
                            'en' => 'Khor Virap',
                        ],
                        'media' => [
                            ['type' => 'image', 'path' => 'tours/khorvirap/point1.jpg'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($tours as $tour) {
            Tour::updateOrCreate(
                ['slug' => Str::slug($tour['name']['en'])],
                array_merge($tour, ['slug' => Str::slug($tour['name']['en'])])
            );
        }
    }
}
