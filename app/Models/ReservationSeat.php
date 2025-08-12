<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationSeat extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_seat_id';

    protected $fillable = [
        'reservation_id',
        'seat_id',
        'table_id', // Add table_id support
        'seat_price',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'seat_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }

    // NEW: Relationship to table
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id', 'table_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->whereHas('reservation', function($q) use ($eventId) {
            $q->where('event_id', $eventId);
        });
    }

    // NEW: Scope for seat reservations only
    public function scopeSeatReservations($query)
    {
        return $query->whereNotNull('seat_id');
    }

    // NEW: Scope for table reservations only
    public function scopeTableReservations($query)
    {
        return $query->whereNotNull('table_id');
    }

    // Helper methods
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->seat_price, 0, ',', '.');
    }

    // NEW: Check if this is a table reservation
    public function isTableReservation(): bool
    {
        return !is_null($this->table_id);
    }

    // NEW: Check if this is a seat reservation
    public function isSeatReservation(): bool
    {
        return !is_null($this->seat_id);
    }

    // NEW: Get the reserved item (seat or table)
    public function getReservedItem()
    {
        if ($this->isTableReservation()) {
            return $this->table;
        } elseif ($this->isSeatReservation()) {
            return $this->seat;
        }
        
        return null;
    }
}