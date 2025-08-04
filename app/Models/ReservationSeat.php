<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationSeat extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_seat_id';

    protected $fillable = [
        'reservation_id',
        'seat_id',
        'seat_price',
    ];

    protected function casts(): array
    {
        return [
            'seat_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }
}