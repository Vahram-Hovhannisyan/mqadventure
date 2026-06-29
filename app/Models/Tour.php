<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'slug', 'badge_color',
        'duration_min', 'duration_max',
        'people_min', 'people_max',
        'price_from', 'is_active', 'sort_order',
        'image', 'route_points',
        'name', 'badge', 'description',
    ];

    protected $casts = [
        'name'         => 'array',
        'badge'        => 'array',
        'description'  => 'array',
        'route_points' => 'array',
        'is_active'    => 'boolean',
    ];

    // ── Translatable helpers ──────────────────────────────────────────────

    public function getName(): string
    {
        return $this->getTranslation('name');
    }

    public function getBadge(): string
    {
        return $this->getTranslation('badge');
    }

    public function getDescription(): string
    {
        return $this->getTranslation('description');
    }

    private function getTranslation(string $field): string
    {
        $locale = app()->getLocale();
        $data   = $this->$field ?? [];
        return $data[$locale] ?? $data['hy'] ?? $data['ru'] ?? $data['en'] ?? '';
    }

    // ── Formatting helpers ────────────────────────────────────────────────

    public function getDuration(): string
    {
        return $this->duration_min === $this->duration_max
            ? (string) $this->duration_min
            : $this->duration_min . '–' . $this->duration_max;
    }

    public function getPeople(): string
    {
        return $this->people_min === $this->people_max
            ? (string) $this->people_min
            : $this->people_min . '–' . $this->people_max;
    }

    public function getPriceFormatted(): string
    {
        return number_format($this->price_from, 0, '.', ' ');
    }

    // ── Route points ──────────────────────────────────────────────────────

    /**
     * Returns route points with translated labels.
     * Each point: [lat, lng, label(string), media([{type,url}])]
     */
    public function getRoutePointsForFront(): array
    {
        $locale = app()->getLocale();
        $points = $this->route_points ?? [];

        return array_map(function ($point) use ($locale) {
            $label = '';
            if (isset($point['label'])) {
                $l = $point['label'];
                $label = $l[$locale] ?? $l['hy'] ?? $l['ru'] ?? $l['en'] ?? '';
            }

            $media = array_map(function ($m) {
                return [
                    'type' => $m['type'] ?? 'image',
                    'url'  => asset('storage/' . $m['path']),
                ];
            }, $point['media'] ?? []);

            return [
                'lat'   => (float) ($point['lat'] ?? 0),
                'lng'   => (float) ($point['lng'] ?? 0),
                'label' => $label,
                'media' => $media,
            ];
        }, $points);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
