<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'layout_id',
        'seat_number',
        'seat_row',
        'seat_type',
        'seat_price',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'seat_price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    // Relationships
    public function layout()
    {
        return $this->belongsTo(SeatLayout::class, 'layout_id', 'layout_id');
    }

    public function reservationSeats()
    {
        return $this->hasMany(ReservationSeat::class, 'seat_id', 'seat_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeVip($query)
    {
        return $query->where('seat_type', 'VIP');
    }

    public function scopeRegular($query)
    {
        return $query->where('seat_type', 'Regular');
    }

    // Helper methods
    public function reserve()
    {
        $this->update(['is_available' => false]);
    }

    public function release()
    {
        $this->update(['is_available' => true]);
    }

    public function isVip()
    {
        return $this->seat_type === 'VIP';
    }
}