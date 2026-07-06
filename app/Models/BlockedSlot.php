<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'tour_id', 'date', 'start_time', 'end_time', 'reason', 'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function isFullDay(): bool
    {
        return is_null($this->start_time) && is_null($this->end_time);
    }

    /**
     * Filters slots that apply to a given tour: global (tour_id null) OR this specific tour.
     */
    public function scopeForTour($query, ?int $tourId)
    {
        return $query->where(function ($q) use ($tourId) {
            $q->whereNull('tour_id');
            if ($tourId) {
                $q->orWhere('tour_id', $tourId);
            }
        });
    }
}
