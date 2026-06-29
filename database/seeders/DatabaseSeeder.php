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
        Admin::firstOrCreate(['email' => 'admin@meghradzor.am'], [
            'name'     => 'Admin',
            'password' => Hash::make('meghradzor2025'),
        ]);

        // ── Default site settings ────────────────────────────────────────
        $settings = [
            ['key' => 'site_title',  'value' => ['value' => 'Meghradzor Quad Adventure'],   'type' => 'text',    'group' => 'general'],
            ['key' => 'admin_email', 'value' => ['value' => 'admin@meghradzor.am'],          'type' => 'text',    'group' => 'general'],
            ['key' => 'phone',       'value' => ['value' => '+374 XX XXX XXX'],              'type' => 'text',    'group' => 'general'],
            ['key' => 'whatsapp',    'value' => ['value' => '+374XXXXXXXXX'],                'type' => 'text',    'group' => 'general'],
            ['key' => 'email',       'value' => ['value' => 'info@meghradzor.am'],           'type' => 'text',    'group' => 'general'],
            ['key' => 'stat1_num',   'value' => ['value' => '500+'],                         'type' => 'text',    'group' => 'hero'],
            ['key' => 'stat2_num',   'value' => ['value' => '12'],                           'type' => 'text',    'group' => 'hero'],
            ['key' => 'stat3_num',   'value' => ['value' => '6'],                            'type' => 'text',    'group' => 'hero'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], [
                'value' => $s['value'], 'type' => $s['type'], 'group' => $s['group'],
            ]);
        }
    }
}
