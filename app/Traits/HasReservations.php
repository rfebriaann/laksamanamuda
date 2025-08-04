<?php

namespace App\Traits;

use App\Models\Reservation;

trait HasReservations
{
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'user_id');
    }

    public function activeReservations()
    {
        return $this->reservations()->whereIn('reservation_status', ['pending', 'confirmed']);
    }

    public function completedReservations()
    {
        return $this->reservations()->where('reservation_status', 'completed');
    }

    public function getTotalSpent()
    {
        return $this->completedReservations()->sum('total_amount');
    }
}