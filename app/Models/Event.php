<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

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
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function seatLayouts()
    {
        return $this->hasMany(SeatLayout::class, 'event_id', 'event_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'event_id', 'event_id');
    }

    // Helper methods
    public function getAvailableSeats()
    {
        return $this->seatLayouts()
            ->with(['seats' => function ($query) {
                $query->where('is_available', true);
            }])
            ->get()
            ->pluck('seats')
            ->flatten();
    }

    public function getTotalSeats()
    {
        return $this->seatLayouts()->withCount('seats')->get()->sum('seats_count');
    }

    public function getBookedSeats()
    {
        return $this->reservations()
            ->where('reservation_status', 'confirmed')
            ->withCount('reservationSeats')
            ->get()
            ->sum('reservation_seats_count');
    }
}