<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $table = 'gallery';

    protected $fillable = ['path', 'thumb_path', 'caption', 'is_active', 'sort_order', 'section'];

    protected $casts = [
        'caption'   => 'array',
        'is_active' => 'boolean',
    ];

    public function trans(string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $data = $this->caption ?? [];
        return $data[$locale] ?? $data['hy'] ?? '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
