<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'email',
        'password',
        'full_name',
        'phone_number',
        'user_type',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function membership()
    {
        return $this->hasOne(Membership::class, 'user_id', 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'user_id');
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by', 'user_id');
    }

    public function createdVouchers()
    {
        return $this->hasMany(Voucher::class, 'created_by', 'user_id');
    }

    public function voucherClaims()
    {
        return $this->hasMany(VoucherClaim::class, 'user_id', 'user_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMembers($query)
    {
        return $query->where('user_type', 'member');
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('user_type', ['admin', 'superadmin']);
    }

    // Helper methods
    public function isAdmin()
    {
        return in_array($this->user_type, ['admin', 'superadmin']);
    }

    public function isMember()
    {
        return $this->user_type === 'member';
    }

    public function hasMembership()
    {
        return $this->membership()->exists();
    }
}