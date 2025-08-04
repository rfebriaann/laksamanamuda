<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatLayout extends Model
{
    use HasFactory;

    protected $primaryKey = 'layout_id';

    protected $fillable = [
        'event_id',
        'layout_name',
        'layout_config',
    ];

    protected function casts(): array
    {
        return [
            'layout_config' => 'array',
        ];
    }

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'layout_id', 'layout_id');
    }
}
