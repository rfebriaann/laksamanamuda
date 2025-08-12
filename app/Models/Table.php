<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $primaryKey = 'table_id';
    
    protected $fillable = [
        'layout_id', 
        'table_number', 
        'capacity', 
        'table_price', 
        'position_x', 
        'position_y',
        'width',
        'height', 
        'shape',
        'is_available',      // 👈 INI YANG PENTING!
        // 'table_metadata'
    ];
    
    // Cast untuk JSON metadata
    // protected $casts = [
    //     'table_metadata' => 'array',
    // ];
    
    public function seats()
    {
        return $this->hasMany(Seat::class, 'table_id');
    }
    
    public function seatLayout()
    {
        return $this->belongsTo(SeatLayout::class, 'layout_id');
    }

    /**
     * Mark table as reserved
     */
    public function reserve()
    {
        \Log::info("Before reserve - Table {$this->table_id}: is_available = {$this->is_available}");
        
        $result = $this->update([
            'is_available' => 0  // Set ke 0 = tidak tersedia
        ]);
        
        \Log::info("After reserve - Table {$this->table_id}: is_available = {$this->fresh()->is_available}, update result: " . ($result ? 'success' : 'failed'));
        
        return $result;
    }

    /**
     * Mark table as available again
     */
    public function makeAvailable()
    {
        return $this->update([
            'is_available' => 1  // Set ke 1 = tersedia
        ]);
    }

    /**
     * Check if table is available
     */
    public function isAvailable()
    {
        return $this->is_available == 1;
    }

    // Scope untuk query
    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }

    public function scopeReserved($query)
    {
        return $query->where('is_available', 0);
    }
}