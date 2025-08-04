<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'reservation_code',
        'user_id',
        'event_id',
        'reservation_status',
        'total_amount',
        'total_seats',
        'reservation_date',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'reservation_date' => 'datetime',
            'expiry_date' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function reservationSeats()
    {
        return $this->hasMany(ReservationSeat::class, 'reservation_id', 'reservation_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id', 'reservation_id');
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'reservation_id', 'reservation_id');
    }

    public function voucherUsage()
    {
        return $this->hasMany(VoucherUsage::class, 'reservation_id', 'reservation_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('reservation_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('reservation_status', 'confirmed');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Helper methods
    public function confirm()
    {
        $this->update(['reservation_status' => 'confirmed']);
        
        // Award points to member if applicable
        if ($this->user->hasMembership()) {
            $this->user->membership->addPoints(
                (int) $this->total_amount,
                "Points earned from reservation {$this->reservation_code}",
                $this->reservation_id
            );
        }
    }

    public function cancel()
    {
        $this->update(['reservation_status' => 'cancelled']);
        
        // Release reserved seats
        foreach ($this->reservationSeats as $reservationSeat) {
            $reservationSeat->seat->release();
        }
    }

    public function isExpired()
    {
        return $this->expiry_date < now();
    }

    public function generateCode()
    {
        return 'LM' . now()->format('Ymd') . strtoupper(uniqid());
    }
}