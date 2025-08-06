<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatLayout extends Model
{
    use HasFactory;

    protected $primaryKey = 'layout_id';

    protected $fillable = [
        'event_id',
        'layout_name',
        'layout_config',
        'selling_mode' ,
    ];

    protected function casts(): array
    {
        return [
            'layout_config' => 'array',
        ];
    }

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class, 'layout_id', 'layout_id');
    }

    // Helper methods for interactive layouts
    
    /**
     * Get layout statistics
     */
    public function getLayoutStats(): array
    {
        $config = $this->layout_config;
        $customSeats = $config['custom_seats'] ?? [];
        
        $totalSeats = count($customSeats);
        $vipSeats = collect($customSeats)->where('type', 'VIP')->count();
        $regularSeats = collect($customSeats)->where('type', 'Regular')->count();
        
        $vipPrice = $config['vip_price'] ?? 300000;
        $regularPrice = $config['regular_price'] ?? 150000;
        
        $estimatedRevenue = ($vipSeats * $vipPrice) + ($regularSeats * $regularPrice);
        
        return [
            'total_seats' => $totalSeats,
            'vip_seats' => $vipSeats,
            'regular_seats' => $regularSeats,
            'estimated_revenue' => $estimatedRevenue,
            'vip_price' => $vipPrice,
            'regular_price' => $regularPrice,
        ];
    }

    /**
     * Check if this is an interactive layout
     */
    public function isInteractiveLayout(): bool
    {
        return isset($this->layout_config['created_with']) && 
               $this->layout_config['created_with'] === 'interactive_designer';
    }

    /**
     * Get available seats count
     */
    public function getAvailableSeatsCount(): int
    {
        return $this->seats()->available()->count();
    }

    /**
     * Get reserved seats count
     */
    public function getReservedSeatsCount(): int
    {
        return $this->seats()->where('is_available', false)->count();
    }

    /**
     * Get VIP seats count
     */
    public function getVipSeatsCount(): int
    {
        return $this->seats()->vip()->count();
    }

    /**
     * Get regular seats count
     */
    public function getRegularSeatsCount(): int
    {
        return $this->seats()->regular()->count();
    }

    /**
     * Calculate potential revenue
     */
    public function getPotentialRevenue(): float
    {
        return $this->seats()->sum('seat_price');
    }

    /**
     * Calculate actual revenue from reservations
     */
    public function getActualRevenue(): float
    {
        return $this->seats()
            ->whereHas('reservationSeats')
            ->sum('seat_price');
    }

    /**
     * Check if layout has any reservations
     */
    public function hasReservations(): bool
    {
        return $this->seats()
            ->whereHas('reservationSeats')
            ->exists();
    }

    /**
     * Get layout type (Grid or Interactive)
     */
    public function getLayoutType(): string
    {
        if ($this->isInteractiveLayout()) {
            return 'Interactive';
        }
        
        // Check if it's a traditional grid layout
        if (isset($this->layout_config['rows']) && isset($this->layout_config['columns'])) {
            return 'Grid';
        }
        
        return 'Custom';
    }

    /**
     * Export layout configuration
     */
    public function exportConfiguration(): array
    {
        return [
            'layout_id' => $this->layout_id,
            'layout_name' => $this->layout_name,
            'layout_type' => $this->getLayoutType(),
            'layout_config' => $this->layout_config,
            'stats' => $this->getLayoutStats(),
            'seats_count' => $this->seats()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Clone layout to another event
     */
    public function cloneToEvent(Event $targetEvent): self
    {
        $newLayout = $this->replicate();
        $newLayout->event_id = $targetEvent->event_id;
        $newLayout->layout_name = $this->layout_name . ' (Clone)';
        $newLayout->save();
        
        // Clone all seats
        foreach ($this->seats as $seat) {
            $newSeat = $seat->replicate();
            $newSeat->layout_id = $newLayout->layout_id;
            $newSeat->save();
        }
        
        return $newLayout;
    }

    /**
     * Get route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'layout_id';
    }
}