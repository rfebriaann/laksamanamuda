<?php
// Berikut adalah contoh tambahan untuk SeatLayoutManager.php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

class SeatLayoutManager extends Component
{
    // Tambahkan WithFileUploads jika ingin menyimpan background image ke server
    use WithFileUploads;
    
    public Event $event;
    public $seatLayouts = [];
    
    #[Rule('required|string|max:255')]
    public $layout_name = '';
    
    public $selling_mode = 'per_seat'; // Default: per kursi
    public $tables = [];
    public $table_price = 500000; // Default harga meja
    public $table_capacity = 4;

    public $vip_price = 300000;
    public $regular_price = 150000;
    public $custom_seats = [];
    
    // Tambahan untuk background image
    public $background_image = null;
    public $save_background = false;
    
    public $showLayoutModal = false;
    public $editingLayoutId = null;

    protected $listeners = [
        'updateSeatPosition',
        'updateTablePosition', 
        'updateLayoutData',
        'syncSeatsAndTables'
    ];
    
    public $eventlocal;
    public function mount(Event $event)
    {
    // dd($this->tables);
    try {
        \Log::info('🚀 SeatLayoutManager mounting', [
            'event_id' => $event->event_id,
            'event_name' => $event->event_name
        ]);

        $this->event = $event;
        
        // Verify event exists
        if (!$this->event) {
            throw new \Exception('Event not found');
        }

        // Load existing layouts
        $this->loadSeatLayouts();
        
        \Log::info('✅ SeatLayoutManager mounted successfully', [
            'layouts_count' => count($this->seatLayouts)
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ Error mounting SeatLayoutManager: ' . $e->getMessage());
        session()->flash('error', 'Error loading seat layout manager: ' . $e->getMessage());
    }
}
    public function render()
    {
        return view('livewire.admin.seat-layout-manager');
    }

//     public function loadSeatLayouts()
// {
//     try {
//         \Log::info('📋 Loading seat layouts for event', ['event_id' => $this->event->event_id]);
        
//         // Check if event exists and has relationships
//         if (!$this->event) {
//             \Log::error('❌ Event not found');
//             $this->seatLayouts = [];
//             return;
//         }

//         // Load layouts with seat count
//         $layouts = \DB::table('seat_layouts as sl')
//             ->leftJoin('seats as s', 's.layout_id', '=', 'sl.layout_id')
//             ->leftJoin('tables as t', 't.layout_id', '=', 'sl.layout_id')
//             ->select(
//                 'sl.*',
//                 \DB::raw('COUNT(DISTINCT s.seat_id) as seats_count'),
//                 \DB::raw('COUNT(DISTINCT t.table_id) as tables_count')
//             )
//             ->where('sl.event_id', $this->event->event_id)
//             ->groupBy('sl.layout_id', 'sl.event_id', 'sl.selling_mode', 'sl.layout_name', 'sl.layout_config', 'sl.created_at', 'sl.updated_at')
//             ->orderBy('sl.created_at', 'desc')
//             ->get();

//         \Log::info('📊 Raw layouts loaded', [
//             'count' => $layouts->count(),
//             'layouts' => $layouts->toArray()
//         ]);

//         // Transform to array format
//         $this->seatLayouts = $layouts->map(function ($layout) {
//             $config = is_string($layout->layout_config) 
//                 ? json_decode($layout->layout_config, true) 
//                 : $layout->layout_config;
                
//             return [
//                 'layout_id' => $layout->layout_id,
//                 'layout_name' => $layout->layout_name,
//                 'selling_mode' => $layout->selling_mode,
//                 'seats_count' => $layout->seats_count ?? 0,
//                 'tables_count' => $layout->tables_count ?? 0,
//                 'layout_config' => $config ?? [],
//                 'created_at' => $layout->created_at,
//                 'updated_at' => $layout->updated_at,
//             ];
//         })->toArray();

//         \Log::info('✅ Seat layouts loaded successfully', [
//             'count' => count($this->seatLayouts),
//             'layouts' => $this->seatLayouts
//         ]);

//     } catch (\Exception $e) {
//         \Log::error('❌ Error loading seat layouts: ' . $e->getMessage(), [
//             'event_id' => $this->event->event_id ?? 'unknown',
//             'trace' => $e->getTraceAsString()
//         ]);
        
//         $this->seatLayouts = [];
//         session()->flash('error', 'Gagal memuat layouts: ' . $e->getMessage());
//     }
// }

    public function createLayout()
    {
        $this->resetForm();
        $this->showLayoutModal = true;
        $this->dispatch('showLayoutModal');
    }

    // public function editLayout($layoutId)
    // {
    //     $layout = SeatLayout::findOrFail($layoutId);
        
    //     $this->editingLayoutId = $layoutId;
    //     $this->layout_name = $layout->layout_name;
    //     $this->selling_mode = $layout->selling_mode;
        
    //     $config = $layout->layout_config;
        
    //     // Load data berdasarkan mode penjualan
    //     if ($layout->selling_mode === 'per_seat') {
    //         $this->vip_price = $config['vip_price'] ?? 300000;
    //         $this->regular_price = $config['regular_price'] ?? 150000;
    //         $this->custom_seats = $config['custom_seats'] ?? [];
    //     } else {
    //         $this->table_price = $config['table_price'] ?? 500000;
    //         $this->tables = $config['tables'] ?? [];
    //         $this->custom_seats = $config['custom_seats'] ?? []; // Tetap load kursi untuk kompabilitas
    //     }
        
    //     $this->showLayoutModal = true;
    //     $this->dispatchBrowserEvent('showLayoutModal');
    // }

    // public function saveLayout()
    // {
    //     $this->validate();

    //     // Validasi tambahan sesuai mode
    //     if ($this->selling_mode === 'per_seat' && empty($this->custom_seats)) {
    //         $this->addError('custom_seats', 'Layout harus memiliki minimal satu kursi.');
    //         return;
    //     } elseif ($this->selling_mode === 'per_table' && empty($this->tables)) {
    //         $this->addError('tables', 'Layout harus memiliki minimal satu meja.');
    //         return;
    //     }

    //     $layoutConfig = [];
        
    //     // Set konfigurasi berdasarkan mode penjualan
    //     if ($this->selling_mode === 'per_seat') {
    //         $layoutConfig = [
    //             'custom_seats' => $this->custom_seats,
    //             'vip_price' => $this->vip_price,
    //             'regular_price' => $this->regular_price,
    //             'created_with' => 'interactive_designer',
    //             'version' => '2.0'
    //         ];
    //     } else {
    //         $layoutConfig = [
    //             'tables' => $this->tables,
    //             'custom_seats' => $this->custom_seats, // Simpan kursi juga untuk data referensi
    //             'table_price' => $this->table_price,
    //             'created_with' => 'interactive_designer',
    //             'version' => '2.0'
    //         ];
    //     }

    //     $layoutData = [
    //         'event_id' => $this->event->event_id,
    //         'layout_name' => $this->layout_name,
    //         'selling_mode' => $this->selling_mode,
    //         'layout_config' => $layoutConfig,
    //     ];

    //     if ($this->editingLayoutId) {
    //         $layout = SeatLayout::findOrFail($this->editingLayoutId);
    //         $layout->update($layoutData);
            
    //         // Delete existing seats and tables
    //         $layout->seats()->delete();
    //         if ($layout->tables) {
    //             $layout->tables()->delete();
    //         }
            
    //         // Generate seats based on mode
    //         if ($this->selling_mode === 'per_table') {
    //             $this->generateTables($layout);
    //         } else {
    //             $this->generateCustomSeats($layout);
    //         }
            
    //         session()->flash('message', 'Layout berhasil diperbarui!');
    //     } else {
    //         $layout = SeatLayout::create($layoutData);
            
    //         // Generate seats based on mode
    //         if ($this->selling_mode === 'per_table') {
    //             $this->generateTables($layout);
    //         } else {
    //             $this->generateCustomSeats($layout);
    //         }
            
    //         session()->flash('message', 'Layout berhasil dibuat!');
    //     }

    //     $this->closeModal();
    //     $this->loadSeatLayouts();
    // }

    private function generateCustomSeats($layout)
{
    \Log::info('🪑 Generating seats for layout', [
        'layout_id' => $layout->layout_id,
        'seats_count' => count($this->custom_seats)
    ]);

    foreach ($this->custom_seats as $seatData) {
        try {
            // Generate seat number based on provided data
            $seatNumber = $seatData['number'] ?? str_pad($seatData['id'] ?? uniqid(), 3, '0', STR_PAD_LEFT);
            $seatRow = $seatData['row'] ?? $this->generateSeatRow($seatData);
            
            $seatRecord = [
                'layout_id' => $layout->layout_id,
                'seat_number' => $seatNumber,
                'seat_row' => $seatRow,
                'seat_type' => $seatData['type'] ?? 'Regular',
                'seat_price' => $seatData['type'] === 'VIP' ? $this->vip_price : $this->regular_price,
                'is_available' => true,
                'position_x' => $seatData['x'] ?? 0,
                'position_y' => $seatData['y'] ?? 0,
                'width' => $seatData['width'] ?? 44,
                'height' => $seatData['height'] ?? 44,
            ];

            $seat = Seat::create($seatRecord);
            \Log::info('✅ Seat created', [
                'seat_id' => $seat->seat_id,
                'seat_number' => $seatNumber,
                'seat_type' => $seatData['type']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Error creating seat: ' . $e->getMessage(), [
                'seat_data' => $seatData
            ]);
            continue;
        }
    }
    
    \Log::info('✅ Seats generation completed');
}

    private function generateSeatRow($seatData)
    {
        // Auto-generate row based on Y position (every 30px = new row)
        $y = $seatData['y'] ?? 0;
        $rowIndex = floor($y / 30);
        return chr(65 + min($rowIndex, 25)); // A-Z
    }

    // Method untuk export layout sebagai PDF
    public function exportLayoutAsPDF($layoutId)
    {
        $layout = SeatLayout::with('seats')->findOrFail($layoutId);
        
        // Gunakan library PDF seperti DOMPDF atau TCPDF
        $pdf = \PDF::loadView('admin.reports.layout-pdf', [
            'layout' => $layout,
            'event' => $this->event
        ]);
        
        return $pdf->download("layout-{$layout->layout_name}.pdf");
    }
    
    // Method untuk export layout sebagai gambar
    public function exportLayoutAsImage($layoutId)
    {
        // Ini hanya contoh. Implementasi sebenarnya memerlukan JavaScript
        // untuk mengambil screenshot dari canvas dan mengirimnya ke server
        $this->dispatch('export-layout-as-image', ['layoutId' => $layoutId]);
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

//     private function generateTables($layout)
// {
//     foreach ($this->tables as $tableData) {
//         // Create the table
//         $table = Table::create([
//             'layout_id' => $layout->layout_id,
//             'table_number' => $tableData['number'] ?? ('Table ' . $tableData['id']),
//             'capacity' => $tableData['capacity'] ?? 4,
//             'table_price' => $this->table_price,
//             'position_x' => $tableData['x'] ?? 0,
//             'position_y' => $tableData['y'] ?? 0,
//         ]);
        
//         // Create seats for this table
//         if (isset($tableData['seats']) && is_array($tableData['seats'])) {
//             foreach ($tableData['seats'] as $seatData) {
//                 Seat::create([
//                     'layout_id' => $layout->layout_id,
//                     'table_id' => $table->table_id,
//                     'seat_number' => $seatData['number'] ?? str_pad($seatData['id'], 3, '0', STR_PAD_LEFT),
//                     'seat_row' => $seatData['row'] ?? $this->generateSeatRow($seatData),
//                     'seat_type' => $seatData['type'] ?? 'Regular',
//                     'seat_price' => 0, // Dalam mode meja, harga seat tidak digunakan
//                     'is_available' => true,
//                     'position_x' => $seatData['x'] ?? 0,
//                     'position_y' => $seatData['y'] ?? 0,
//                 ]);
//             }
//         }
//     }
// }

    public function previewLayout($layoutId)
    {
        // Could redirect to a preview page or open modal
        // dd("Previewing layout with ID: {$this->event->event_id} and layout ID: {$layoutId}");
        $eventid = $this->event->event_id;
        return redirect()->route('event.seat-layout.preview', [
            'event' => $eventid,
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
    \Log::info('🔄 Resetting form data');
    
    $this->editingLayoutId = null;
    $this->layout_name = '';
    $this->selling_mode = 'per_seat';
    $this->vip_price = 300000;
    $this->regular_price = 150000;
    $this->table_price = 500000;
    $this->table_capacity = 4;
    $this->custom_seats = [];
    $this->tables = [];
    $this->resetErrorBag();
    
    \Log::info('✅ Form reset completed');
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
                    'seat_shape' => $seat->seat_shape ?? 'default',
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

    // Validation for layout
    // protected function rules()
    // {
    //     $rules = [
    //         'layout_name' => 'required|string|max:255',
    //         'selling_mode' => 'required|in:per_seat,per_table',
    //     ];
        
    //     if ($this->selling_mode === 'per_seat') {
    //         $rules['custom_seats'] = 'array|min:1';
    //         $rules['vip_price'] = 'required|numeric|min:0';
    //         $rules['regular_price'] = 'required|numeric|min:0';
    //     } else {
    //         $rules['table_price'] = 'required|numeric|min:0';
    //         // Validasi tables tidak dilakukan disini karena data tables dikelola oleh JavaScript
    //     }
        
    //     return $rules;
    // }

    // protected function messages()
    // {
    //     return [
    //         'layout_name.required' => 'Nama layout harus diisi.',
    //         'custom_seats.min' => 'Layout harus memiliki minimal satu kursi.',
    //         'vip_price.required' => 'Harga VIP harus diisi.',
    //         'regular_price.required' => 'Harga Regular harus diisi.',
    //         'vip_price.min' => 'Harga VIP tidak boleh negatif.',
    //         'regular_price.min' => 'Harga Regular tidak boleh negatif.',
    //         'background_image.image' => 'File harus berupa gambar.',
    //         'background_image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
    //     ];
    // }

    // tambahan terbaru 

    public function updateSeatPosition($seatId, $x, $y)
{
    // Find seat in custom_seats array and update position
    $customSeats = collect($this->custom_seats);
    $seatIndex = $customSeats->search(function ($seat) use ($seatId) {
        return $seat['id'] === $seatId;
    });
    
    if ($seatIndex !== false) {
        $this->custom_seats[$seatIndex]['x'] = $x;
        $this->custom_seats[$seatIndex]['y'] = $y;
    }
}

// Method to update individual table position  
public function updateTablePosition($tableId, $x, $y)
{
    // Find table in tables array and update position
    $tables = collect($this->tables);
    $tableIndex = $tables->search(function ($table) use ($tableId) {
        return $table['id'] === $tableId;
    });
    
    if ($tableIndex !== false) {
        $this->tables[$tableIndex]['x'] = $x;
        $this->tables[$tableIndex]['y'] = $y;
        
        // Update associated seats positions relative to table
        if (isset($this->tables[$tableIndex]['seats'])) {
            foreach ($this->tables[$tableIndex]['seats'] as $seatIndex => $seat) {
                // Update seat position in custom_seats as well
                $this->updateSeatPosition($seat['id'], $seat['x'], $seat['y']);
            }
        }
    }
}

// Method to sync complete layout data from JavaScript
public function updateLayoutData($seats, $tables = [])
{
    \Log::info('🔄 updateLayoutData called from JavaScript', [
        'seats_count' => count($seats),
        'tables_count' => count($tables),
        'selling_mode' => $this->selling_mode
    ]);

    if ($this->selling_mode === 'per_table') {
        $this->tables = $tables;
        $this->custom_seats = []; // Clear seats in table mode
    } else {
        $this->custom_seats = $seats;
        $this->tables = []; // Clear tables in seat mode
    }
    
    \Log::info('✅ Layout data updated successfully');
}

// Method to receive data from JavaScript before save
public function syncSeatsAndTables($data)
{
    \Log::info('🔄 syncSeatsAndTables called', [
        'data_keys' => array_keys($data),
        'selling_mode' => $this->selling_mode
    ]);

    if ($this->selling_mode === 'per_table') {
        if (isset($data['tables'])) {
            $this->tables = $data['tables'];
            \Log::info('✅ Tables synced', ['count' => count($this->tables)]);
        }
        $this->custom_seats = []; // Clear seats
    } else {
        if (isset($data['seats'])) {
            $this->custom_seats = $data['seats'];
            \Log::info('✅ Seats synced', ['count' => count($this->custom_seats)]);
        }
        $this->tables = []; // Clear tables
    }
}

// Enhanced generateTables method with proper error handling
// private function generateTablesForLayout($layoutId)
// {
//     \Log::info('🍽️ Generating tables for layout', [
//         'layout_id' => $layoutId,
//         'tables_count' => count($this->tables)
//     ]);

//     $insertData = [];
//     $now = now();

//     foreach ($this->tables as $index => $tableData) {
//         try {
//             $tableRecord = [
//                 'layout_id' => $layoutId,
//                 'table_number' => $tableData['number'] ?? 'T' . ($index + 1),
//                 'capacity' => (int) ($tableData['capacity'] ?? 4),
//                 'table_price' => (float) $this->table_price,
//                 'position_x' => (float) ($tableData['x'] ?? 0),
//                 'position_y' => (float) ($tableData['y'] ?? 0),
//                 'created_at' => $now,
//                 'updated_at' => $now
//             ];

//             $insertData[] = $tableRecord;
            
//             \Log::info('✅ Table data prepared', [
//                 'index' => $index,
//                 'table_number' => $tableRecord['table_number'],
//                 'capacity' => $tableRecord['capacity']
//             ]);
            
//         } catch (\Exception $e) {
//             \Log::error('❌ Error preparing table data: ' . $e->getMessage(), [
//                 'index' => $index,
//                 'table_data' => $tableData
//             ]);
//             continue;
//         }
//     }

//     if (!empty($insertData)) {
//         try {
//             \DB::table('tables')->insert($insertData);
//             \Log::info('✅ Tables inserted successfully', ['count' => count($insertData)]);
//         } catch (\Exception $e) {
//             \Log::error('❌ Error inserting tables: ' . $e->getMessage());
//             throw $e;
//         }
//     }
// }



// Enhanced validation rules with dynamic rules based on selling mode
protected function rules()
{
    $rules = [
        'layout_name' => 'required|string|max:255',
        'selling_mode' => 'required|in:per_seat,per_table',
    ];
    
    if ($this->selling_mode === 'per_seat') {
        $rules['vip_price'] = 'required|numeric|min:0';
        $rules['regular_price'] = 'required|numeric|min:0';
    } else {
        $rules['table_price'] = 'required|numeric|min:0';
        $rules['table_capacity'] = 'required|integer|min:2|max:12';
    }
    
    return $rules;
}

// Enhanced validation messages
protected function messages()
{
    return [
        'layout_name.required' => 'Nama layout harus diisi.',
        'layout_name.max' => 'Nama layout tidak boleh lebih dari 255 karakter.',
        'selling_mode.required' => 'Mode penjualan harus dipilih.',
        'selling_mode.in' => 'Mode penjualan tidak valid.',
        'vip_price.required' => 'Harga VIP harus diisi.',
        'regular_price.required' => 'Harga Regular harus diisi.',
        'vip_price.min' => 'Harga VIP tidak boleh negatif.',
        'regular_price.min' => 'Harga Regular tidak boleh negatif.',
        'table_price.required' => 'Harga meja harus diisi.',
        'table_price.min' => 'Harga meja tidak boleh negatif.',
        'table_capacity.required' => 'Kapasitas meja harus diisi.',
        'table_capacity.integer' => 'Kapasitas meja harus berupa angka.',
        'table_capacity.min' => 'Kapasitas meja minimal 2.',
        'table_capacity.max' => 'Kapasitas meja maksimal 12.',
    ];
}

// Method to get layout statistics for display
public function getLayoutStatistics($layoutConfig, $sellingMode)
{
    $stats = [
        'total_seats' => 0,
        'total_tables' => 0,
        'total_capacity' => 0,
        'regular_seats' => 0,
        'vip_seats' => 0,
        'estimated_revenue' => 0,
        'shapes_breakdown' => []
    ];

    if ($sellingMode === 'per_table') {
        $tables = $layoutConfig['tables'] ?? [];
        $stats['total_tables'] = count($tables);
        
        // Count total capacity and shapes
        foreach ($tables as $table) {
            $capacity = $table['capacity'] ?? 4;
            $stats['total_capacity'] += $capacity;
            
            $shape = $table['shape'] ?? 'square';
            if (!isset($stats['shapes_breakdown'][$shape])) {
                $stats['shapes_breakdown'][$shape] = 0;
            }
            $stats['shapes_breakdown'][$shape]++;
        }
        
        $tablePrice = $layoutConfig['table_price'] ?? 500000;
        $stats['estimated_revenue'] = $stats['total_tables'] * $tablePrice;
        
    } else {
        // per_seat mode
        $customSeats = $layoutConfig['custom_seats'] ?? [];
        $stats['total_seats'] = count($customSeats);
        
        foreach ($customSeats as $seat) {
            if (($seat['type'] ?? 'Regular') === 'VIP') {
                $stats['vip_seats']++;
            } else {
                $stats['regular_seats']++;
            }
        }
        
        $vipPrice = $layoutConfig['vip_price'] ?? 300000;
        $regularPrice = $layoutConfig['regular_price'] ?? 150000;
        $stats['estimated_revenue'] = ($stats['vip_seats'] * $vipPrice) + ($stats['regular_seats'] * $regularPrice);
    }

    return $stats;
}

// Method to export layout with complete data structure
public function exportLayoutAsJSON($layoutId)
{
    $layout = SeatLayout::with('seats')->findOrFail($layoutId);
    
    $exportData = [
        'layout_info' => [
            'name' => $layout->layout_name,
            'selling_mode' => $layout->selling_mode,
            'created_at' => $layout->created_at->toISOString(),
            'event_name' => $this->event->event_name,
            'event_date' => $this->event->event_date->format('Y-m-d'),
        ],
        'layout_config' => $layout->layout_config,
        'seats' => $layout->seats->map(function ($seat) {
            return [
                'seat_number' => $seat->seat_number,
                'seat_row' => $seat->seat_row,
                'seat_type' => $seat->seat_type,
                'seat_shape' => $seat->seat_shape ?? 'default',
                'position_x' => $seat->position_x ?? 0,
                'position_y' => $seat->position_y ?? 0,
                'table_id' => $seat->table_id ?? null,
            ];
        }),
        'statistics' => $this->getLayoutStatistics($layout->layout_config, $layout->selling_mode),
        'export_metadata' => [
            'exported_at' => now()->toISOString(),
            'exported_by' => auth()->user()->name ?? 'Unknown',
            'version' => '2.0'
        ]
    ];
    
    $filename = "layout-{$layout->layout_name}-" . now()->format('Y-m-d-H-i-s') . '.json';
    
    return response()->json($exportData)
        ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
}

// Method to import layout from JSON
public function importLayoutFromJSON($jsonData)
{
    try {
        $data = json_decode($jsonData, true);
        
        if (!$data || !isset($data['layout_config'])) {
            throw new \Exception('Invalid layout data format');
        }
        
        // Create new layout from imported data
        $layoutData = [
            'event_id' => $this->event->event_id,
            'layout_name' => $data['layout_info']['name'] . ' (Imported)',
            'selling_mode' => $data['layout_info']['selling_mode'] ?? 'per_seat',
            'layout_config' => $data['layout_config'],
        ];

        $layout = SeatLayout::create($layoutData);
        
        // Generate seats based on imported data
        if ($layout->selling_mode === 'per_table') {
            $this->tables = $data['layout_config']['tables'] ?? [];
            $this->table_price = $data['layout_config']['table_price'] ?? 500000;
            $this->generateTables($layout);
        } else {
            $this->custom_seats = $data['layout_config']['custom_seats'] ?? [];
            $this->vip_price = $data['layout_config']['vip_price'] ?? 300000;
            $this->regular_price = $data['layout_config']['regular_price'] ?? 150000;
            $this->generateCustomSeats($layout);
        }
        
        session()->flash('message', 'Layout berhasil diimpor!');
        $this->loadSeatLayouts();
        
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal mengimpor layout: ' . $e->getMessage());
    }
}

// Enhanced save layout with better error handling
// public function saveLayout()
// {
//     \Log::info('🔄 saveLayout method called', [
//         'selling_mode' => $this->selling_mode,
//         'seats_count' => count($this->custom_seats ?? []),
//         'tables_count' => count($this->tables ?? []),
//         'layout_name' => $this->layout_name,
//         'event_id' => $this->event->event_id ?? 'unknown'
//     ]);

//     try {
//         // Validate form
//         $this->validate();

//         // Additional validation based on mode
//         if ($this->selling_mode === 'per_seat' && empty($this->custom_seats)) {
//             $this->addError('custom_seats', 'Layout harus memiliki minimal satu kursi.');
//             return;
//         } elseif ($this->selling_mode === 'per_table' && empty($this->tables)) {
//             $this->addError('tables', 'Layout harus memiliki minimal satu meja.');
//             return;
//         }

//         // Start database transaction
//         \DB::beginTransaction();

//         $layoutConfig = [
//             'custom_seats' => $this->selling_mode === 'per_seat' ? $this->custom_seats : [],
//             'tables' => $this->selling_mode === 'per_table' ? $this->tables : [],
//             'vip_price' => $this->vip_price,
//             'regular_price' => $this->regular_price,
//             'table_price' => $this->table_price,
//             'table_capacity' => $this->table_capacity,
//             'created_with' => 'interactive_designer',
//             'version' => '2.0',
//         ];

//         $layoutData = [
//             'event_id' => $this->event->event_id,
//             'layout_name' => trim($this->layout_name),
//             'selling_mode' => $this->selling_mode,
//             'layout_config' => json_encode($layoutConfig),
//             'created_at' => now(),
//             'updated_at' => now(),
//         ];

//         if ($this->editingLayoutId) {
//             // Update existing layout
//             \DB::table('seat_layouts')
//                 ->where('layout_id', $this->editingLayoutId)
//                 ->update($layoutData);
//         } else {
//             // Create new layout
//             $this->editingLayoutId = \DB::table('seat_layouts')->insertGetId($layoutData);
//         }

//         // Generate seats/tables based on mode
//         if ($this->selling_mode === 'per_table') {
//             $this->generateTablesForLayout($this->editingLayoutId);
//         } else {
//             $this->generateCustomSeats($this->editingLayoutId);
//         }

//         \DB::commit();
//         session()->flash('message', 'Layout berhasil disimpan!');
//     } catch (\Exception $e) {
//         \DB::rollback();
//         \Log::error('❌ Error saving layout: ' . $e->getMessage());
//         session()->flash('error', 'Gagal menyimpan layout: ' . $e->getMessage());
//     }

//     $this->closeModal();
//     $this->loadSeatLayouts();
// }


// Method to validate layout data
private function validateLayoutData()
{
    $errors = [];
    
    if ($this->selling_mode === 'per_seat') {
        if (empty($this->custom_seats)) {
            $errors[] = 'Layout harus memiliki minimal satu kursi.';
        }
        
        // Validate each seat
        foreach ($this->custom_seats as $index => $seat) {
            if (!isset($seat['x']) || !isset($seat['y'])) {
                $errors[] = "Kursi #$index tidak memiliki posisi yang valid.";
            }
            if (!in_array($seat['type'] ?? '', ['Regular', 'VIP'])) {
                $errors[] = "Kursi #$index memiliki tipe yang tidak valid.";
            }
        }
    } else {
        if (empty($this->tables)) {
            $errors[] = 'Layout harus memiliki minimal satu meja.';
        }
        
        // Validate each table
        foreach ($this->tables as $index => $table) {
            if (!isset($table['x']) || !isset($table['y'])) {
                $errors[] = "Meja #$index tidak memiliki posisi yang valid.";
            }
            if (!isset($table['capacity']) || $table['capacity'] < 2 || $table['capacity'] > 12) {
                $errors[] = "Meja #$index memiliki kapasitas yang tidak valid (2-12).";
            }
        }
    }
    
    return $errors;
}

// Enhanced load layout for editing
// public function editLayout($layoutId)
// {
//     try {
//         $layout = SeatLayout::findOrFail($layoutId);
        
//         $this->editingLayoutId = $layoutId;
//         $this->layout_name = $layout->layout_name;
//         $this->selling_mode = $layout->selling_mode;
        
//         $config = $layout->layout_config;
        
//         // Load data based on selling mode
//         if ($layout->selling_mode === 'per_seat') {
//             $this->vip_price = $config['vip_price'] ?? 300000;
//             $this->regular_price = $config['regular_price'] ?? 150000;
//             $this->custom_seats = $config['custom_seats'] ?? [];
//             $this->tables = []; // Clear tables for per_seat mode
//         } else {
//             // per_table mode
//             $this->table_price = $config['table_price'] ?? 500000;
//             $this->table_capacity = $config['table_capacity'] ?? 4;
//             $this->tables = $config['tables'] ?? [];
//             $this->custom_seats = []; // No individual seats in per_table mode
//         }
        
//         $this->showLayoutModal = true;
        
//         // Dispatch event to JavaScript to load layout data
//         $this->dispatch('loadLayoutData', [
//             'seats' => $this->custom_seats,
//             'tables' => $this->tables,
//             'sellingMode' => $this->selling_mode
//         ]);
        
//     } catch (\Exception $e) {
//         session()->flash('error', 'Gagal memuat layout: ' . $e->getMessage());
//     }
// }


// Method untuk load seat layouts dengan debugging lengkap
public function loadSeatLayouts()
{
    try {
        \Log::info('📋 Loading seat layouts for event', ['event_id' => $this->event->event_id]);
        
        // Check if event exists and has relationships
        if (!$this->event) {
            \Log::error('❌ Event not found');
            $this->seatLayouts = [];
            return;
        }

        // Load layouts with seat count
        $layouts = \DB::table('seat_layouts as sl')
            ->leftJoin('seats as s', 's.layout_id', '=', 'sl.layout_id')
            ->leftJoin('tables as t', 't.layout_id', '=', 'sl.layout_id')
            ->select(
                'sl.*',
                \DB::raw('COUNT(DISTINCT s.seat_id) as seats_count'),
                \DB::raw('COUNT(DISTINCT t.table_id) as tables_count')
            )
            ->where('sl.event_id', $this->event->event_id)
            ->groupBy('sl.layout_id', 'sl.event_id', 'sl.selling_mode', 'sl.layout_name', 'sl.layout_config', 'sl.created_at', 'sl.updated_at')
            ->orderBy('sl.created_at', 'desc')
            ->get();

        \Log::info('📊 Raw layouts loaded', [
            'count' => $layouts->count(),
            'layouts' => $layouts->toArray()
        ]);

        // Transform to array format
        $this->seatLayouts = $layouts->map(function ($layout) {
            $config = is_string($layout->layout_config) 
                ? json_decode($layout->layout_config, true) 
                : $layout->layout_config;
                
            return [
                'layout_id' => $layout->layout_id,
                'layout_name' => $layout->layout_name,
                'selling_mode' => $layout->selling_mode,
                'seats_count' => $layout->seats_count ?? 0,
                'tables_count' => $layout->tables_count ?? 0,
                'layout_config' => $config ?? [],
                'created_at' => $layout->created_at,
                'updated_at' => $layout->updated_at,
            ];
        })->toArray();

        \Log::info('✅ Seat layouts loaded successfully', [
            'count' => count($this->seatLayouts),
            'layouts' => $this->seatLayouts
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ Error loading seat layouts: ' . $e->getMessage(), [
            'event_id' => $this->event->event_id ?? 'unknown',
            'trace' => $e->getTraceAsString()
        ]);
        
        $this->seatLayouts = [];
        session()->flash('error', 'Gagal memuat layouts: ' . $e->getMessage());
    }
}

// Method untuk edit layout yang sudah diperbaiki
public function editLayout($layoutId)
{
    try {
        $layout = SeatLayout::findOrFail($layoutId);
        
        $this->editingLayoutId = $layoutId;
        $this->layout_name = $layout->layout_name;
        $this->selling_mode = $layout->selling_mode;
        
        $config = $layout->layout_config;
        
        // Load data based on selling mode
        if ($layout->selling_mode === 'per_seat') {
            $this->vip_price = $config['vip_price'] ?? 300000;
            $this->regular_price = $config['regular_price'] ?? 150000;
            $this->custom_seats = $config['custom_seats'] ?? [];
            $this->tables = []; // Clear tables for per_seat mode
        } else {
            // per_table mode
            $this->table_price = $config['table_price'] ?? 500000;
            $this->table_capacity = $config['table_capacity'] ?? 4;
            $this->tables = $config['tables'] ?? [];
            $this->custom_seats = []; // No individual seats in per_table mode
        }
        
        $this->showLayoutModal = true;
        
        // Dispatch event to JavaScript to load layout data
        $this->dispatch('loadLayoutData', [
            'seats' => $this->custom_seats,
            'tables' => $this->tables,
            'sellingMode' => $this->selling_mode
        ]);
        
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal memuat layout: ' . $e->getMessage());
    }
}

// Method saveLayout yang sudah diperbaiki dengan debugging lengkap
public function saveLayout()
{
    dd("Save layout called with selling mode: {$this->custom_seats}, layout name: {$this->layout_name}");
    \Log::info('🔄 saveLayout method called', [
        'selling_mode' => $this->selling_mode,
        'seats_count' => count($this->custom_seats ?? []),
        'tables_count' => count($this->tables ?? []),
        'layout_name' => $this->layout_name,
        'event_id' => $this->event->event_id ?? 'unknown'
    ]);

    try {
        // Log current data state
        \Log::info('📊 Current form data:', [
            'layout_name' => $this->layout_name,
            'selling_mode' => $this->selling_mode,
            'vip_price' => $this->vip_price,
            'regular_price' => $this->regular_price,
            'table_price' => $this->table_price,
            'table_capacity' => $this->table_capacity,
            'custom_seats' => $this->custom_seats,
            'tables' => $this->tables
        ]);

        // Validate form
        $validationRules = $this->rules();
        \Log::info('🔍 Validation rules:', $validationRules);
        
        $this->validate();
        \Log::info('✅ Basic validation passed');

        // Additional validation based on mode
        if ($this->selling_mode === 'per_seat') {
            if (empty($this->custom_seats)) {
                \Log::warning('❌ Validation failed: No seats in per_seat mode');
                $this->addError('custom_seats', 'Layout harus memiliki minimal satu kursi.');
                return;
            }
            \Log::info('✅ Per-seat validation passed', ['seats_count' => count($this->custom_seats)]);
        } else {
            if (empty($this->tables)) {
                \Log::warning('❌ Validation failed: No tables in per_table mode');
                $this->addError('tables', 'Layout harus memiliki minimal satu meja.');
                return;
            }
            \Log::info('✅ Per-table validation passed', ['tables_count' => count($this->tables)]);
        }

        // Start database transaction
        \DB::beginTransaction();
        \Log::info('📊 Database transaction started');

        $layoutConfig = [];
        
        // Set configuration based on selling mode
        if ($this->selling_mode === 'per_seat') {
            $layoutConfig = [
                'custom_seats' => $this->custom_seats,
                'vip_price' => (int) $this->vip_price,
                'regular_price' => (int) $this->regular_price,
                'created_with' => 'interactive_designer',
                'version' => '2.0',
                'created_at' => now()->toISOString()
            ];
            \Log::info('✅ Per-seat layout config created', [
                'seats_count' => count($this->custom_seats),
                'config_size' => strlen(json_encode($layoutConfig))
            ]);
        } else {
            $layoutConfig = [
                'tables' => $this->tables,
                'table_price' => (int) $this->table_price,
                'table_capacity' => (int) $this->table_capacity,
                'created_with' => 'interactive_designer', 
                'version' => '2.0',
                'created_at' => now()->toISOString()
            ];
            \Log::info('✅ Per-table layout config created', [
                'tables_count' => count($this->tables),
                'config_size' => strlen(json_encode($layoutConfig))
            ]);
        }

        $layoutData = [
            'event_id' => $this->event->event_id,
            'layout_name' => trim($this->layout_name),
            'selling_mode' => $this->selling_mode,
            'layout_config' => $layoutConfig,
            'updated_at' => now()
        ];


        \Log::info('📝 Layout data prepared:', [
            'event_id' => $layoutData['event_id'],
            'layout_name' => $layoutData['layout_name'],
            'selling_mode' => $layoutData['selling_mode'],
            'config_keys' => array_keys($layoutConfig)
        ]);

        if ($this->editingLayoutId) {
            // Update existing layout
            \Log::info('🔄 Updating existing layout', ['layout_id' => $this->editingLayoutId]);
            
            $layout = \DB::table('seat_layouts')
                ->where('layout_id', $this->editingLayoutId)
                ->first();
                
            if (!$layout) {
                throw new \Exception("Layout dengan ID {$this->editingLayoutId} tidak ditemukan");
            }
            
            \DB::table('seat_layouts')
                ->where('layout_id', $this->editingLayoutId)
                ->update($layoutData);
                
            \Log::info('✅ Layout updated in database');
            
            // Delete existing seats and tables
            $deletedSeats = \DB::table('seats')
                ->where('layout_id', $this->editingLayoutId)
                ->delete();
                
            $deletedTables = \DB::table('tables')
                ->where('layout_id', $this->editingLayoutId)
                ->delete();
                
            \Log::info('🗑️ Existing data cleaned', [
                'deleted_seats' => $deletedSeats,
                'deleted_tables' => $deletedTables
            ]);
            
            $layoutId = $this->editingLayoutId;
            $message = 'Layout berhasil diperbarui!';
        } else {
            // Create new layout
            \Log::info('🆕 Creating new layout');
            
            $layoutData['created_at'] = now();
            
            $layoutId = \DB::table('seat_layouts')->insertGetId($layoutData);
            \Log::info('✅ New layout created', ['layout_id' => $layoutId]);
            $message = 'Layout berhasil dibuat!';
        }
        
        // Generate seats/tables based on mode
        if ($this->selling_mode === 'per_table') {
            $this->generateTablesForLayout($layoutId);
            \Log::info('🍽️ Tables generated for layout');
        } else {
            $this->generateSeatsForLayout($layoutId);
            \Log::info('🪑 Seats generated for layout');
        }
        
        \DB::commit();
        \Log::info('✅ Transaction committed successfully');
        session()->flash('message', $message);
        
    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error('❌ Error saving layout: ' . $e->getMessage(), [
            'selling_mode' => $this->selling_mode,
            'layout_name' => $this->layout_name,
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('error', 'Gagal menyimpan layout: ' . $e->getMessage());
        return;
    }

    $this->closeModal();
    $this->loadSeatLayouts();
}

// Method generateTablesForLayout yang diperbaiki
private function generateTablesForLayout($layoutId)
{
    \Log::info('🍽️ Generating tables for layout', [
        'layout_id' => $layoutId,
        'tables_count' => count($this->tables)
    ]);

    $insertData = [];
    $now = now();

    foreach ($this->tables as $index => $tableData) {
        try {
            $tableRecord = [
                'layout_id' => $layoutId,
                'table_number' => $tableData['number'] ?? 'T' . ($index + 1),
                'capacity' => (int) ($tableData['capacity'] ?? 4),
                'table_price' => (float) $this->table_price,
                'position_x' => (float) ($tableData['x'] ?? 0),
                'position_y' => (float) ($tableData['y'] ?? 0),
                'created_at' => $now,
                'updated_at' => $now
            ];

            $insertData[] = $tableRecord;
            
            \Log::info('✅ Table data prepared', [
                'index' => $index,
                'table_number' => $tableRecord['table_number'],
                'capacity' => $tableRecord['capacity']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Error preparing table data: ' . $e->getMessage(), [
                'index' => $index,
                'table_data' => $tableData
            ]);
            continue;
        }
    }

    if (!empty($insertData)) {
        try {
            \DB::table('tables')->insert($insertData);
            \Log::info('✅ Tables inserted successfully', ['count' => count($insertData)]);
        } catch (\Exception $e) {
            \Log::error('❌ Error inserting tables: ' . $e->getMessage());
            throw $e;
        }
    }
}

// Method generateSeatsForLayout yang diperbaiki
private function generateSeatsForLayout($layoutId)
{
    \Log::info('🪑 Generating seats for layout', [
        'layout_id' => $layoutId,
        'seats_count' => count($this->custom_seats)
    ]);

    $insertData = [];
    $now = now();

    foreach ($this->custom_seats as $index => $seatData) {
        try {
            $seatType = $seatData['type'] ?? 'Regular';
            $seatPrice = $seatType === 'VIP' ? $this->vip_price : $this->regular_price;
            
            $seatRecord = [
                'layout_id' => $layoutId,
                'seat_number' => $seatData['number'] ?? ($index + 1),
                'seat_row' => $seatData['row'] ?? $this->generateSeatRow($seatData),
                'seat_type' => $seatType,
                'seat_price' => (float) $seatPrice,
                'is_available' => true,
                'position_x' => (int) ($seatData['x'] ?? 0),
                'position_y' => (int) ($seatData['y'] ?? 0),
                'seat_metadata' => json_encode([
                    'width' => $seatData['width'] ?? 44,
                    'height' => $seatData['height'] ?? 44,
                    'shape' => $seatData['shape'] ?? 'square'
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ];

            $insertData[] = $seatRecord;
            
            \Log::info('✅ Seat data prepared', [
                'index' => $index,
                'seat_number' => $seatRecord['seat_number'],
                'seat_type' => $seatRecord['seat_type']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Error preparing seat data: ' . $e->getMessage(), [
                'index' => $index,
                'seat_data' => $seatData
            ]);
            continue;
        }
    }

    if (!empty($insertData)) {
        try {
            \DB::table('seats')->insert($insertData);
            \Log::info('✅ Seats inserted successfully', ['count' => count($insertData)]);
        } catch (\Exception $e) {
            \Log::error('❌ Error inserting seats: ' . $e->getMessage());
            throw $e;
        }
    }
}

}