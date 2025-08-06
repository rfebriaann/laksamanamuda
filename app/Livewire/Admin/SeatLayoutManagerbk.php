<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use Livewire\Component;
use Livewire\Attributes\Rule;

class SeatLayoutManager extends Component
{
    #[On('updateSeats')]
    public $listeners = ['updateSeats'];
    public Event $event;
    public $seatLayouts = [];
    
    #[Rule('required|string|max:255')]
    public $layout_name = '';
    
    public $rows;
    public $columns; // Default columns, can be adjusted
    public $vip_rows = [];
    public $vip_price = 300000;
    public $regular_price = 150000;
    public $custom_seats = [];
    
    public $showLayoutModal = false;
    public $editingLayoutId = null;
    public function updateSeats($seats)
    {
        $this->custom_seats = $seats;
    }
    public function mount(Event $event)
    {
        $this->columns = 20; // Default columns, can be adjusted
        $this->rows = 10; // Default rows, can be adjusted
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

    public function updateSeatPosition($seatId, $x, $y)
    {
        $seat = Seat::find($seatId);
        if ($seat) {
            $seat->position_x = $x;
            $seat->position_y = $y;
            $seat->save();
        }
    }

    public function editLayout($layoutId)
    {
        $layout = SeatLayout::findOrFail($layoutId);
        
        $this->editingLayoutId = $layoutId;
        $this->layout_name = $layout->layout_name;
        
        $config = $layout->layout_config;
        $this->vip_price = $config['vip_price'] ?? 300000;
        $this->regular_price = $config['regular_price'] ?? 150000;
        $this->custom_seats = $config['custom_seats'] ?? [];
        
        $this->showLayoutModal = true;
    }

    public function saveLayout()
    {
        $this->validate();

        // Validate that we have at least one seat
        if (empty($this->custom_seats)) {
            $this->addError('custom_seats', 'Layout harus memiliki minimal satu kursi.');
            return;
        }

        $layoutData = [
            'event_id' => $this->event->event_id,
            'layout_name' => $this->layout_name,
            'layout_config' => [
                'custom_seats' => $this->custom_seats,
                'vip_price' => $this->vip_price,
                'regular_price' => $this->regular_price,
                'created_with' => 'interactive_designer',
                'version' => '2.0'
            ],
        ];

        if ($this->editingLayoutId) {
            $layout = SeatLayout::findOrFail($this->editingLayoutId);
            $layout->update($layoutData);
            
            // Delete existing seats and recreate them
            $layout->seats()->delete();
            $this->generateCustomSeats($layout);
            
            session()->flash('message', 'Layout berhasil diperbarui!');
        } else {
            $layout = SeatLayout::create($layoutData);
            $this->generateCustomSeats($layout);
            session()->flash('message', 'Layout berhasil dibuat!');
        }

        $this->closeModal();
        $this->loadSeatLayouts();
    }

    private function generateCustomSeats($layout)
    {
        foreach ($this->custom_seats as $seatData) {
            // Generate seat number based on position or use provided data
            $seatNumber = $this->generateSeatNumber($seatData);
            $seatRow = $this->generateSeatRow($seatData);
            
            Seat::create([
                'layout_id' => $layout->layout_id,
                'seat_number' => $seatNumber,
                'seat_row' => $seatRow,
                'seat_type' => $seatData['type'] ?? 'Regular',
                'seat_price' => $seatData['type'] === 'VIP' ? $this->vip_price : $this->regular_price,
                'is_available' => true,
                'position_x' => $seatData['x'] ?? 0,
                'position_y' => $seatData['y'] ?? 0,
            ]);
        }
    }

    private function generateSeatNumber($seatData)
    {
        // Use provided number or generate based on ID
        return $seatData['number'] ?? str_pad($seatData['id'], 3, '0', STR_PAD_LEFT);
    }

    private function generateSeatRow($seatData)
    {
        // Use provided row or generate based on Y position
        if (isset($seatData['row'])) {
            return $seatData['row'];
        }
        
        // Auto-generate row based on Y position (every 30px = new row)
        $y = $seatData['y'] ?? 0;
        $rowIndex = floor($y / 30);
        return chr(65 + min($rowIndex, 25)); // A-Z
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

    public function duplicateLayout($layoutId)
    {
        $layout = SeatLayout::findOrFail($layoutId);
        
        $newLayout = $layout->replicate();
        $newLayout->layout_name = $layout->layout_name . ' (Copy)';
        $newLayout->save();
        
        // Copy all seats
        foreach ($layout->seats as $seat) {
            $newSeat = $seat->replicate();
            $newSeat->layout_id = $newLayout->layout_id;
            $newSeat->save();
        }
        
        session()->flash('message', 'Layout berhasil diduplikasi!');
        $this->loadSeatLayouts();
    }

    public function previewLayout($layoutId)
    {
        // Could redirect to a preview page or open modal
        return redirect()->route('admin.events.seat-layout.preview', [
            'event' => $this->event,
            'layout' => $layoutId
        ]);
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
        $this->vip_price = 300000;
        $this->regular_price = 150000;
        $this->custom_seats = [];
        $this->resetErrorBag();
    }

    // Helper method to get layout statistics
    public function getLayoutStats($layoutConfig)
    {
        $customSeats = $layoutConfig['custom_seats'] ?? [];
        
        $totalSeats = count($customSeats);
        $vipSeats = collect($customSeats)->where('type', 'VIP')->count();
        $regularSeats = collect($customSeats)->where('type', 'Regular')->count();
        
        $vipPrice = $layoutConfig['vip_price'] ?? 300000;
        $regularPrice = $layoutConfig['regular_price'] ?? 150000;
        
        $estimatedRevenue = ($vipSeats * $vipPrice) + ($regularSeats * $regularPrice);
        
        return [
            'total_seats' => $totalSeats,
            'vip_seats' => $vipSeats,
            'regular_seats' => $regularSeats,
            'estimated_revenue' => $estimatedRevenue,
            'vip_price' => $vipPrice,
            'regular_price' => $regularPrice,
        ];
    }

    // Method to export layout as JSON
    public function exportLayout($layoutId)
    {
        $layout = SeatLayout::with('seats')->findOrFail($layoutId);
        
        $exportData = [
            'layout_name' => $layout->layout_name,
            'layout_config' => $layout->layout_config,
            'seats' => $layout->seats->map(function ($seat) {
                return [
                    'seat_number' => $seat->seat_number,
                    'seat_row' => $seat->seat_row,
                    'seat_type' => $seat->seat_type,
                    'position_x' => $seat->position_x ?? 0,
                    'position_y' => $seat->position_y ?? 0,
                ];
            }),
            'exported_at' => now()->toISOString(),
            'event_name' => $this->event->event_name,
        ];
        
        $filename = "layout-{$layout->layout_name}-" . now()->format('Y-m-d-H-i-s') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // Validation for custom seats
    protected function rules()
    {
        return [
            'layout_name' => 'required|string|max:255',
            'vip_price' => 'required|numeric|min:0',
            'regular_price' => 'required|numeric|min:0',
            'custom_seats' => 'array|min:1',
            'custom_seats.*.id' => 'required|integer',
            'custom_seats.*.x' => 'required|numeric|min:0',
            'custom_seats.*.y' => 'required|numeric|min:0',
            'custom_seats.*.type' => 'required|in:Regular,VIP',
        ];
    }

    protected function messages()
    {
        return [
            'layout_name.required' => 'Nama layout harus diisi.',
            'custom_seats.min' => 'Layout harus memiliki minimal satu kursi.',
            'custom_seats.*.type.in' => 'Tipe kursi harus Regular atau VIP.',
            'vip_price.required' => 'Harga VIP harus diisi.',
            'regular_price.required' => 'Harga Regular harus diisi.',
            'vip_price.min' => 'Harga VIP tidak boleh negatif.',
            'regular_price.min' => 'Harga Regular tidak boleh negatif.',
        ];
    }
}