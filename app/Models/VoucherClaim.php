<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    use HasFactory;

    protected $primaryKey = 'claim_id';

    protected $fillable = [
        'voucher_id',
        'user_id',
        'membership_id',
        'claim_status',
        'claimed_at',
        'used_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'claimed_at' => 'datetime',
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // Relationships
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'membership_id');
    }

    public function voucherUsage()
    {
        return $this->hasMany(VoucherUsage::class, 'claim_id', 'claim_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('claim_status', 'active')
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
                    ->orWhere('claim_status', 'expired');
    }

    // Helper methods
    public function isValid()
    {
        return $this->claim_status === 'active' && $this->expires_at > now();
    }

    public function use($reservationId, $discountApplied)
    {
        $this->update([
            'claim_status' => 'used',
            'used_at' => now(),
        ]);

        return $this->voucherUsage()->create([
            'reservation_id' => $reservationId,
            'discount_applied' => $discountApplied,
            'used_at' => now(),
        ]);
    }

    public function expire()
    {
        $this->update(['claim_status' => 'expired']);
    }
}