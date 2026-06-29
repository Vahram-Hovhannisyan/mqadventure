<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tour_id', 'name', 'phone', 'date', 'people', 'comment', 'status', 'locale', 'ip',
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
}
