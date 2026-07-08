<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SiteSetting;
use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ───────────────────────────────────────────────────
        Admin::firstOrCreate(['email' => 'karapetyangurgen6@gmail.com'], [
            'name'     => 'Gurgen',
            'password' => Hash::make('gurgen1998'),
        ]);

        Admin::firstOrCreate(['email' => 'hovhannisyand556@gmail.com'], [
            'name'     => 'Davo',
            'password' => Hash::make('davo1998'),
        ]);

        Admin::firstOrCreate(['email' => 'vrm99ov@gmail.com'], [
            'name'     => 'Vahram',
            'password' => Hash::make('vahram12'),
        ]);

        // ── Default site settings ────────────────────────────────────────
        $settings = [
            ['key' => 'site_title',  'value' => ['value' => 'Meghradzor Quad Adventure'],   'type' => 'text',    'group' => 'general'],
            ['key' => 'admin_email', 'value' => ['value' => 'mqadventure@gmail.com'],          'type' => 'text',    'group' => 'general'],
            ['key' => 'phone',       'value' => ['value' => '+374 94 818 985'],              'type' => 'text',    'group' => 'general'],
            ['key' => 'instagram',    'value' => ['value' => 'https://www.instagram.com/meghradzor_quad_adventure?igsh=ZnRicmJsY2lseDFn'],                'type' => 'text',    'group' => 'general'],
            ['key' => 'facebook',    'value' => ['value' => 'https://www.facebook.com/share/19NzVXfP4k/'],                'type' => 'text',    'group' => 'general'],
            ['key' => 'whatsapp',    'value' => ['value' => '+37494818985'],                'type' => 'text',    'group' => 'general'],
            ['key' => 'email',       'value' => ['value' => 'mqadventure@gmail.com'],           'type' => 'text',    'group' => 'general'],
            ['key' => 'stat1_num',   'value' => ['value' => '100+'],                         'type' => 'text',    'group' => 'hero'],
            ['key' => 'stat2_num',   'value' => ['value' => '5'],                           'type' => 'text',    'group' => 'hero'],
            ['key' => 'stat3_num',   'value' => ['value' => '3'],                            'type' => 'text',    'group' => 'hero'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], [
                'value' => $s['value'], 'type' => $s['type'], 'group' => $s['group'],
            ]);
        }
    }
}
