<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    use HasFactory;

    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'layout_id', 
        'table_id', 
        'seat_number', 
        'seat_row', 
        'seat_type', 
        'seat_price',
        'is_available', 
        'position_x', 
        'position_y'
    ];

    protected function casts(): array
    {
        return [
            'seat_price' => 'decimal:2',
            'is_available' => 'boolean',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'seat_metadata' => 'array',
        ];
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    
    // Relationships
    public function layout(): BelongsTo
    {
        return $this->belongsTo(SeatLayout::class, 'layout_id', 'layout_id');
    }

    public function reservationSeats(): HasMany
    {
        return $this->hasMany(ReservationSeat::class, 'seat_id', 'seat_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeReserved($query)
    {
        return $query->where('is_available', false);
    }

    public function scopeVip($query)
    {
        return $query->where('seat_type', 'VIP');
    }

    public function scopeRegular($query)
    {
        return $query->where('seat_type', 'Regular');
    }

    public function scopePremium($query)
    {
        return $query->where('seat_type', 'Premium');
    }

    // Position-based scopes for interactive layouts
    public function scopeInArea($query, $x1, $y1, $x2, $y2)
    {
        return $query->where('position_x', '>=', $x1)
                    ->where('position_x', '<=', $x2)
                    ->where('position_y', '>=', $y1)
                    ->where('position_y', '<=', $y2);
    }

    public function scopeNearPosition($query, $x, $y, $radius = 50)
    {
        return $query->whereRaw("
            SQRT(POWER(position_x - ?, 2) + POWER(position_y - ?, 2)) <= ?
        ", [$x, $y, $radius]);
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

    public function isVip(): bool
    {
        return $this->seat_type === 'VIP';
    }

    public function isRegular(): bool
    {
        return $this->seat_type === 'Regular';
    }

    public function isPremium(): bool
    {
        return $this->seat_type === 'Premium';
    }

    public function isReserved(): bool
    {
        return !$this->is_available;
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    /**
     * Get seat display name (Row + Number)
     */
    public function getDisplayName(): string
    {
        return $this->seat_row . $this->seat_number;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->seat_price, 0, ',', '.');
    }

    /**
     * Check if seat has position data (interactive layout)
     */
    public function hasPosition(): bool
    {
        return !is_null($this->position_x) && !is_null($this->position_y);
    }

    /**
     * Get position as array
     */
    public function getPosition(): array
    {
        return [
            'x' => $this->position_x ?? 0,
            'y' => $this->position_y ?? 0,
        ];
    }

    /**
     * Set position
     */
    public function setPosition(int $x, int $y): void
    {
        $this->update([
            'position_x' => $x,
            'position_y' => $y,
        ]);
    }

    /**
     * Get distance from another seat
     */
    public function getDistanceFrom(Seat $otherSeat): float
    {
        if (!$this->hasPosition() || !$otherSeat->hasPosition()) {
            return 0;
        }

        $dx = $this->position_x - $otherSeat->position_x;
        $dy = $this->position_y - $otherSeat->position_y;

        return sqrt(pow($dx, 2) + pow($dy, 2));
    }

    /**
     * Get nearby seats within radius
     */
    public function getNearbySeats(int $radius = 50)
    {
        if (!$this->hasPosition()) {
            return collect();
        }

        return $this->layout->seats()
            ->nearPosition($this->position_x, $this->position_y, $radius)
            ->where('seat_id', '!=', $this->seat_id)
            ->get();
    }

    /**
     * Check if seat is adjacent to another seat
     */
    public function isAdjacentTo(Seat $otherSeat, int $maxDistance = 30): bool
    {
        return $this->getDistanceFrom($otherSeat) <= $maxDistance;
    }

    /**
     * Get seat metadata value
     */
    public function getMetadata(string $key, $default = null)
    {
        return data_get($this->seat_metadata, $key, $default);
    }

    /**
     * Set seat metadata value
     */
    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->seat_metadata ?? [];
        data_set($metadata, $key, $value);
        $this->update(['seat_metadata' => $metadata]);
    }

    /**
     * Export seat data for frontend
     */
    public function exportForFrontend(): array
    {
        return [
            'id' => $this->seat_id,
            'number' => $this->seat_number,
            'row' => $this->seat_row,
            'type' => $this->seat_type,
            'price' => $this->seat_price,
            'is_available' => $this->is_available,
            'position' => $this->getPosition(),
            'display_name' => $this->getDisplayName(),
            'formatted_price' => $this->getFormattedPrice(),
        ];
    }

    /**
     * Get route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'seat_id';
    }
}