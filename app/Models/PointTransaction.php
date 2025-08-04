<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'point_transaction_id';

    protected $fillable = [
        'membership_id',
        'reservation_id',
        'transaction_type',
        'points_amount',
        'description',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
        ];
    }

    // Relationships
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'membership_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    // Scopes
    public function scopeEarned($query)
    {
        return $query->where('transaction_type', 'earned');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('transaction_type', 'redeemed');
    }
}