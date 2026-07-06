<?php

// app/Models/Quad.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quad extends Model
{
    protected $fillable = ['name', 'image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_quad');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
