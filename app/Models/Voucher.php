<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'voucher_code',
        'voucher_name',
        'voucher_description',
        'voucher_type',
        'required_points',
        'discount_amount',
        'discount_type',
        'valid_from',
        'valid_until',
        'max_usage',
        'current_usage',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2',
            'valid_from' => 'date',
            'valid_until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function voucherClaims()
    {
        return $this->hasMany(VoucherClaim::class, 'voucher_id', 'voucher_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now());
    }

    public function scopePointRedemption($query)
    {
        return $query->where('voucher_type', 'point_redemption');
    }

    public function scopeFreeClaim($query)
    {
        return $query->where('voucher_type', 'free_claim');
    }

    // Helper methods
    public function isValid()
    {
        return $this->is_active && 
               $this->valid_from <= now() && 
               $this->valid_until >= now() &&
               ($this->max_usage === null || $this->current_usage < $this->max_usage);
    }

    public function canBeClaimed()
    {
        return $this->isValid();
    }

    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return ($amount * $this->discount_amount) / 100;
        }
        
        return min($this->discount_amount, $amount);
    }

    public function incrementUsage()
    {
        $this->increment('current_usage');
    }
}