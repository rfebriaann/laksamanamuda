<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'reservation_id',
        'xendit_payment_id',
        'amount',
        'payment_status',
        'payment_method',
        'payment_url',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'datetime',
        ];
    }

    // Relationships
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Helper methods
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        // Confirm the reservation
        $this->reservation->confirm();
    }

    public function markAsFailed()
    {
        $this->update(['payment_status' => 'failed']);
        $this->reservation->cancel();
    }
}