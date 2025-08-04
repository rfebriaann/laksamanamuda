<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $primaryKey = 'membership_id';

    protected $fillable = [
        'user_id',
        'membership_number',
        'total_points',
        'joined_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'membership_id', 'membership_id');
    }

    public function voucherClaims()
    {
        return $this->hasMany(VoucherClaim::class, 'membership_id', 'membership_id');
    }

    // Helper methods
    public function addPoints($amount, $description, $reservationId = null)
    {
        $this->increment('total_points', $amount);
        
        return $this->pointTransactions()->create([
            'reservation_id' => $reservationId,
            'transaction_type' => 'earned',
            'points_amount' => $amount,
            'description' => $description,
            'transaction_date' => now(),
        ]);
    }

    public function deductPoints($amount, $description)
    {
        if ($this->total_points < $amount) {
            throw new \Exception('Insufficient points');
        }

        $this->decrement('total_points', $amount);
        
        return $this->pointTransactions()->create([
            'transaction_type' => 'redeemed',
            'points_amount' => $amount,
            'description' => $description,
            'transaction_date' => now(),
        ]);
    }

    public function hasEnoughPoints($amount)
    {
        return $this->total_points >= $amount;
    }
}