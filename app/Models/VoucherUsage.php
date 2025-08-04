<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    use HasFactory;

    protected $table = 'voucher_usage';
    protected $primaryKey = 'usage_id';

    protected $fillable = [
        'claim_id',
        'reservation_id',
        'discount_applied',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_applied' => 'decimal:2',
            'used_at' => 'datetime',
        ];
    }

    // Relationships
    public function claim()
    {
        return $this->belongsTo(VoucherClaim::class, 'claim_id', 'claim_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }
}