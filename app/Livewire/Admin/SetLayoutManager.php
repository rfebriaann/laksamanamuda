<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use Livewire\Component;
use Livewire\Attributes\Rule;

class SeatLayoutManager extends Component
{
    public Event $event;
    public $seatLayouts = [];
    
    #[Rule('required|string|max:255')]
    public $layout_name = '';
    
    public $rows = 10;
    public $columns = 20;
    public $vip_rows = [];
    public $vip_price = 300000;
    public $regular_price = 150000;
    
    public $showLayoutModal = false;
    public $editingLayoutId = null;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadSeatLayouts();
    }

    public function render()
    {
        return view('livewire.admin.seat-layout-manager');
    }

    public function loadSeatLayouts()
    {
        $this->seatLayouts = $this->event->seatLayouts()
            ->withCount('seats')
            ->get()
            ->toArray();
    }

    public function createLayout()
    {
        $this->resetForm();
        $this->showLayoutModal = true;
    }

    public function editLayout($layoutId)
    {
        $layout = SeatLayout::findOrFail($layoutId);
        
        $this->editingLayoutId = $layoutId;
        $this->layout_name = $layout->layout_name;
        
        $config = $layout->layout_config;
        $this->rows = $config['rows'] ?? 10;
        $this->columns = $config['columns'] ?? 20;
        $this->vip_rows = $config['vip_rows'] ?? [];
        
        // Get prices from existing seats
        $vipSeat = $layout->seats()->where('seat_type', 'VIP')->first();
        $regularSeat = $layout->seats()->where('seat_type', 'Regular')->first();
        
        $this->vip_price = $vipSeat?->seat_price ?? 300000;
        $this->regular_price = $regularSeat?->seat_price ?? 150000;
        
        $this->showLayoutModal = true;
    }

    public function saveLayout()
    {
        $this->validate();

        $layoutData = [
            'event_id' => $this->event->event_id,
            'layout_name' => $this->layout_name,
            'layout_config' => [
                'rows' => $this->rows,
                'columns' => $this->columns,
                'vip_rows' => $this->vip_rows,
                'vip_price' => $this->vip_price,
                'regular_price' => $this->regular_price,
            ],
        ];

        if ($this->editingLayoutId) {
            $layout = SeatLayout::findOrFail($this->editingLayoutId);
            $layout->update($layoutData);
            
            // Update existing seats prices
            $layout->seats()->where('seat_type', 'VIP')->update(['seat_price' => $this->vip_price]);
            $layout->seats()->where('seat_type', 'Regular')->update(['seat_price' => $this->regular_price]);
            
            session()->flash('message', 'Layout berhasil diperbarui!');
        } else {
            $layout = SeatLayout::create($layoutData);
            $this->generateSeats($layout);
            session()->flash('message', 'Layout berhasil dibuat!');
        }

        $this->closeModal();
        $this->loadSeatLayouts();
    }

    private function generateSeats($layout)
    {
        $seatNumber = 1;
        
        for ($row = 1; $row <= $this->rows; $row++) {
            for ($col = 1; $col <= $this->columns; $col++) {
                $isVip = in_array($row, $this->vip_rows);
                
                Seat::create([
                    'layout_id' => $layout->layout_id,
                    'seat_number' => str_pad($seatNumber, 3, '0', STR_PAD_LEFT),
                    'seat_row' => chr(65 + $row - 1), // A, B, C, etc.
                    'seat_type' => $isVip ? 'VIP' : 'Regular',
                    'seat_price' => $isVip ? $this->vip_price : $this->regular_price,
                    'is_available' => true,
                ]);
                
                $seatNumber++;
            }
        }
    }

    public function deleteLayout($layoutId)
    {
        $layout = SeatLayout::findOrFail($layoutId);
        
        // Check if layout has any reservations
        $hasReservations = $layout->seats()
            ->whereHas('reservationSeats')
            ->exists();
            
        if ($hasReservations) {
            session()->flash('error', 'Tidak dapat menghapus layout yang sudah memiliki reservasi!');
            return;
        }
        
        $layout->delete();
        session()->flash('message', 'Layout berhasil dihapus!');
        $this->loadSeatLayouts();
    }

    public function closeModal()
    {
        $this->showLayoutModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingLayoutId = null;
        $this->layout_name = '';
        $this->rows = 10;
        $this->columns = 20;
        $this->vip_rows = [];
        $this->vip_price = 300000;
        $this->regular_price = 150000;
        $this->resetErrorBag();
    }
}