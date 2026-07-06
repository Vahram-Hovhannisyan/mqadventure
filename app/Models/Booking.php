<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tour_id', 'name', 'phone', 'date', 'time', 'duration_hours', 'quads_used',
        'people', 'comment', 'status', 'locale', 'ip',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    const STATUSES = ['new', 'confirmed', 'cancelled', 'completed'];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new'       => __('booking.status_new'),
            'confirmed' => __('booking.status_confirmed'),
            'cancelled' => __('booking.status_cancelled'),
            'completed' => __('booking.status_completed'),
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'new'       => 'orange',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'gray',
            default     => 'gray',
        };
    }

    public function scopeActiveStatus($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    /**
     * Effective duration in hours: uses the value stored on the booking if present
     * (may have been manually adjusted by an admin), otherwise falls back to the
     * tour's duration_max.
     */
    public function getEffectiveDurationHours(): float
    {
        if ($this->duration_hours) {
            return (float) $this->duration_hours;
        }

        return (float) ($this->tour?->duration_max ?? $this->tour?->duration_min ?? 4);
    }

    public function getStartDateTime(): ?Carbon
    {
        if (!$this->date || !$this->time) {
            return null;
        }

        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->time);
    }

    public function getEndDateTime(): ?Carbon
    {
        $start = $this->getStartDateTime();
        if (!$start) {
            return null;
        }

        return $start->copy()->addMinutes((int) round($this->getEffectiveDurationHours() * 60));
    }

    // в App\Models\Booking
    public function quads()
    {
        return $this->belongsToMany(Quad::class, 'booking_quad');
    }
}
