<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    // Update primary key to match your existing structure
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_description',
        'venue_name',
        'venue_address',
        'event_date',
        'start_time',
        'end_time',
        'event_image',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the user who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all seat layouts for this event.
     */
    public function seatLayouts(): HasMany
    {
        return $this->hasMany(SeatLayout::class, 'event_id', 'event_id');
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->format('Y-m-d'));
    }

    /**
     * Check if event is upcoming
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->event_date->isFuture();
    }

    /**
     * Check if event is today
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->event_date->isToday();
    }

    /**
     * Check if event is past
     */
    public function getIsPastAttribute(): bool
    {
        return $this->event_date->isPast();
    }

    /**
     * Get route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'event_id';
    }
}