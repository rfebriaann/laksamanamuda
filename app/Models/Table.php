<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $primaryKey = 'table_id';
    protected $fillable = ['layout_id', 'table_number', 'table_type', 'capacity', 'table_price', 'position_x', 'position_y'];
    
    public function seats()
    {
        return $this->hasMany(Seat::class, 'table_id');
    }
    
    public function seatLayout()
    {
        return $this->belongsTo(SeatLayout::class, 'layout_id');
    }
}
