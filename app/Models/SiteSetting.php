<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    protected $casts = ['value' => 'array'];

    /**
     * Get translated value for a key, with cache.
     */
    public static function get(string $key, string $locale = null, $default = null): mixed
    {
        $locale ??= app()->getLocale();

        $all = Cache::remember('site_settings', 3600, function () {
            return static::all()->keyBy('key');
        });

        $setting = $all->get($key);
        if (!$setting) return $default;

        $value = $setting->value;

        // Translatable field
        if (isset($value['hy']) || isset($value['ru']) || isset($value['en'])) {
            return $value[$locale] ?? $value['hy'] ?? $default;
        }

        // Plain value
        return $value['value'] ?? $default;
    }

    /**
     * Update or create a setting and bust cache.
     */
    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], [
            'value' => is_array($value) ? $value : ['value' => $value],
            'type'  => $type,
            'group' => $group,
        ]);
        Cache::forget('site_settings');
    }
}
