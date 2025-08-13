<?php

namespace App\Livewire\App;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

#[Title('Pilih Kursi - Reservasi Event')]
#[Layout('layouts.app')]
class SeatReservation extends Component
{
    public Event $event;
    public $layouts = [];
    public $selectedLayout = null;
    public $selectedSeats = [];
    public $selectedTables = [];
    public $reservedSeats = [];
    public $reservedTables = [];
    public $totalAmount = 0;
    public $totalSeats = 0;
    public $sellingMode = 'per_seat';
    
    // Customer info (jika user belum login atau guest checkout)
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    
    // UI State
    public $showBookingSummary = false;
    public $isProcessing = false;
    public $reservationSuccess = false;
    public $reservationCode = '';

    protected $rules = [
        'customerName' => 'required_if:showBookingSummary,true|string|max:255',
        'customerEmail' => 'required_if:showBookingSummary,true|email|max:255',
        'customerPhone' => 'required_if:showBookingSummary,true|string|max:20',
    ];

    protected $messages = [
        'customerName.required_if' => 'Nama lengkap harus diisi.',
        'customerEmail.required_if' => 'Email harus diisi.',
        'customerPhone.required_if' => 'Nomor telepon harus diisi.',
    ];

    public function getRules()
    {
        $rules = [
            'customerName' => 'required_if:showBookingSummary,true|string|max:255',
            'customerEmail' => 'required_if:showBookingSummary,true|email|max:255',  
            'customerPhone' => 'required_if:showBookingSummary,true|string|max:20',
        ];

        // FIXED: Dynamic validation berdasarkan selling mode
        if ($this->sellingMode === 'per_table') {
            $rules['selectedTables'] = 'required|array|min:1';
        } else {
            $rules['selectedSeats'] = 'required|array|min:1';
        }

        return $rules;
    }

    public function getMessages()
    {
        $messages = [
            'customerName.required_if' => 'Nama lengkap harus diisi.',
            'customerEmail.required_if' => 'Email harus diisi.',
            'customerPhone.required_if' => 'Nomor telepon harus diisi.',
        ];

        // FIXED: Dynamic messages berdasarkan selling mode
        if ($this->sellingMode === 'per_table') {
            $messages['selectedTables.required'] = 'Silakan pilih minimal satu meja.';
            $messages['selectedTables.min'] = 'Silakan pilih minimal satu meja.';
        } else {
            $messages['selectedSeats.required'] = 'Silakan pilih minimal satu kursi.';
            $messages['selectedSeats.min'] = 'Silakan pilih minimal satu kursi.';
        }

        return $messages;
    }

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadLayouts();
        $this->loadReservedItems();
        
        // Auto fill customer info if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name ?? '';
            $this->customerEmail = $user->email ?? '';
            $this->customerPhone = $user->phone ?? '';
        }
    }

   public function loadLayouts()
{
    try {
        // Load layouts dengan seats dan tables dari database
        $layouts = SeatLayout::where('event_id', $this->event->event_id)->get();
        
        $this->layouts = [];
        
        foreach ($layouts as $layout) {
            $layoutData = [
                'layout_id' => $layout->layout_id,
                'layout_name' => $layout->layout_name,
                'layout_config' => $layout->layout_config,
                'background_image' => $layout->background_image
            ];
            
            // FIXED: Detect selling mode dari data yang ada
            $hasSeats = Seat::where('layout_id', $layout->layout_id)->exists();
            $hasTables = Table::where('layout_id', $layout->layout_id)->exists();
            
            if ($hasTables) {
                // Jika ada tables, maka mode per_table
                $layoutData['layout_config']['selling_mode'] = 'per_table';
                $this->sellingMode = 'per_table';
                
                // PRIORITASKAN data dari database untuk availability
                $dbTables = Table::where('layout_id', $layout->layout_id)->get()->keyBy('table_id');
                
                if (isset($layout->layout_config['tables']) && !empty($layout->layout_config['tables'])) {
                    // Gunakan data dari JSON config tapi update availability dari database
                    $configTables = $layout->layout_config['tables'];
                    $tablesArray = [];
                    
                    foreach ($configTables as $configTable) {
                        $dbTable = null;
                        
                        // Cari di database berdasarkan ID atau posisi
                        if (isset($configTable['id']) && is_numeric($configTable['id'])) {
                            $dbTable = $dbTables->get($configTable['id']);
                        }
                        
                        if (!$dbTable) {
                            // Fallback: cari berdasarkan posisi dan nomor
                            $dbTable = $dbTables->first(function($table) use ($configTable) {
                                return $table->position_x == $configTable['x'] 
                                    && $table->position_y == $configTable['y']
                                    && $table->table_number == $configTable['number'];
                            });
                        }
                        
                        $tablesArray[] = [
                            'id' => $dbTable ? $dbTable->table_id : $configTable['id'],
                            'x' => $configTable['x'],
                            'y' => $configTable['y'],
                            'number' => $configTable['number'],
                            'capacity' => $configTable['capacity'],
                            'shape' => $configTable['shape'] ?? 'square',
                            'width' => $configTable['width'] ?? 120,
                            'height' => $configTable['height'] ?? 120,
                            'price' => $dbTable ? $dbTable->table_price : ($layout->layout_config['table_price'] ?? 500000),
                            // FIXED: Always get availability from database
                            'is_available' => $dbTable ? (bool)$dbTable->is_available : true
                        ];
                    }
                    
                    $layoutData['layout_config']['tables'] = $tablesArray;
                    
                } else {
                    // Fallback ke database saja
                    $tablesArray = [];
                    
                    foreach ($dbTables as $table) {
                        $metadata = json_decode($table->table_metadata, true) ?? [];
                        
                        $tablesArray[] = [
                            'id' => $table->table_id,
                            'x' => $table->position_x,
                            'y' => $table->position_y,
                            'number' => $table->table_number,
                            'capacity' => $table->capacity,
                            'shape' => $metadata['shape'] ?? $table->shape ?? 'square',
                            'width' => $table->width ?? 120,
                            'height' => $table->height ?? 120,
                            'price' => $table->table_price,
                            // FIXED: Get from database
                            'is_available' => (bool)$table->is_available
                        ];
                    }
                    
                    $layoutData['layout_config']['tables'] = $tablesArray;
                }
                
            } elseif ($hasSeats) {
                // Jika ada seats, maka mode per_seat
                $layoutData['layout_config']['selling_mode'] = 'per_seat';
                $this->sellingMode = 'per_seat';
                
                // PRIORITASKAN data dari database untuk availability
                $dbSeats = Seat::where('layout_id', $layout->layout_id)->get()->keyBy('seat_id');
                
                if (isset($layout->layout_config['custom_seats']) && !empty($layout->layout_config['custom_seats'])) {
                    // Gunakan data dari JSON config tapi update availability dari database
                    $configSeats = $layout->layout_config['custom_seats'];
                    $seatsArray = [];
                    
                    foreach ($configSeats as $configSeat) {
                        $dbSeat = null;
                        
                        // Cari di database berdasarkan ID atau posisi
                        if (isset($configSeat['id']) && is_numeric($configSeat['id'])) {
                            $dbSeat = $dbSeats->get($configSeat['id']);
                        }
                        
                        if (!$dbSeat) {
                            // Fallback: cari berdasarkan posisi dan nomor
                            $dbSeat = $dbSeats->first(function($seat) use ($configSeat) {
                                return $seat->position_x == $configSeat['x'] 
                                    && $seat->position_y == $configSeat['y']
                                    && $seat->seat_number == $configSeat['number'];
                            });
                        }
                        
                        $seatsArray[] = [
                            'id' => $dbSeat ? $dbSeat->seat_id : $configSeat['id'],
                            'x' => $configSeat['x'],
                            'y' => $configSeat['y'],
                            'type' => $configSeat['type'],
                            'number' => $configSeat['number'],
                            'row' => $configSeat['row'] ?? null,
                            'width' => $configSeat['width'] ?? 44,
                            'height' => $configSeat['height'] ?? 44,
                            'price' => $dbSeat ? $dbSeat->seat_price : ($configSeat['type'] === 'VIP' ? ($layout->layout_config['vip_price'] ?? 300000) : ($layout->layout_config['regular_price'] ?? 150000)),
                            // FIXED: Always get availability from database
                            'is_available' => $dbSeat ? (bool)$dbSeat->is_available : true
                        ];
                    }
                    
                    $layoutData['layout_config']['custom_seats'] = $seatsArray;
                    
                } else {
                    // Fallback ke database saja
                    $seatsArray = [];
                    
                    foreach ($dbSeats as $seat) {
                        $seatsArray[] = [
                            'id' => $seat->seat_id,
                            'x' => $seat->position_x,
                            'y' => $seat->position_y,
                            'type' => $seat->seat_type,
                            'number' => $seat->seat_number,
                            'width' => $seat->width ?? 44,
                            'height' => $seat->height ?? 44,
                            'price' => $seat->seat_price,
                            // FIXED: Get from database
                            'is_available' => (bool)$seat->is_available
                        ];
                    }
                    
                    $layoutData['layout_config']['custom_seats'] = $seatsArray;
                }
            }
            
            $this->layouts[] = $layoutData;
        }

        if (!empty($this->layouts)) {
            $this->selectedLayout = $this->layouts[0];
            // Ensure selling mode is set from the detected mode
            $this->sellingMode = $this->selectedLayout['layout_config']['selling_mode'] ?? 'per_seat';
        }

        Log::info('Layouts loaded with database availability sync', [
            'event_id' => $this->event->event_id,
            'layouts_count' => count($this->layouts),
            'selling_mode' => $this->sellingMode,
            'has_seats' => $hasSeats ?? false,
            'has_tables' => $hasTables ?? false,
            'availability_synced' => true
        ]);

    } catch (Exception $e) {
        Log::error('Error loading layouts: ' . $e->getMessage());
        session()->flash('error', 'Gagal memuat layout kursi.');
    }
}

    public function loadReservedItems()
{
    try {
        // Get reserved seats
        $this->reservedSeats = DB::table('reservation_seats as rs')
            ->join('reservations as r', 'rs.reservation_id', '=', 'r.reservation_id')
            ->where('r.event_id', $this->event->event_id)
            ->whereIn('r.reservation_status', ['pending', 'confirmed'])
            ->where('r.expire_date', '>', now())
            ->whereNotNull('rs.seat_id')  // FIXED: Only get seat reservations
            ->pluck('rs.seat_id')
            ->toArray();

        // FIXED: Get reserved tables correctly
        $this->reservedTables = DB::table('reservation_seats as rs')
            ->join('reservations as r', 'rs.reservation_id', '=', 'r.reservation_id')
            ->where('r.event_id', $this->event->event_id)
            ->whereIn('r.reservation_status', ['pending', 'confirmed'])
            ->where('r.expire_date', '>', now())
            ->whereNotNull('rs.table_id')  // FIXED: Only get table reservations
            ->pluck('rs.table_id')
            ->toArray();

        // ALTERNATIVE: Jika struktur database berbeda, gunakan cara ini
        // $this->reservedTables = DB::table('tables as t')
        //     ->where('t.is_available', 0)
        //     ->whereExists(function($query) {
        //         $query->select('*')
        //               ->from('seat_layouts as sl')
        //               ->where('sl.event_id', $this->event->event_id)
        //               ->whereRaw('sl.layout_id = t.layout_id');
        //     })
        //     ->pluck('t.table_id')
        //     ->toArray();

        Log::info('Reserved items loaded', [
            'event_id' => $this->event->event_id,
            'reserved_seats' => $this->reservedSeats,
            'reserved_tables' => $this->reservedTables,
            'reserved_seats_count' => count($this->reservedSeats),
            'reserved_tables_count' => count($this->reservedTables)
        ]);

    } catch (Exception $e) {
        Log::error('Error loading reserved items: ' . $e->getMessage());
        
        // Set default empty arrays if error
        $this->reservedSeats = [];
        $this->reservedTables = [];
    }
}

    public function selectLayout($layoutIndex)
    {
        if (isset($this->layouts[$layoutIndex])) {
            $this->selectedLayout = $this->layouts[$layoutIndex];
            $this->sellingMode = $this->selectedLayout['layout_config']['selling_mode'] ?? 'per_seat';
            $this->clearSelections();
        }
    }

    public function toggleSeatSelection($seatId)
{
    // FIXED: Convert to integer for consistency
    $seatId = (int) $seatId;
    
    \Log::info('Toggle seat selection', [
        'seat_id' => $seatId,
        'seat_id_type' => gettype($seatId),
        'reserved_seats' => $this->reservedSeats,
        'is_reserved' => in_array($seatId, $this->reservedSeats),
        'selected_seats_before' => $this->selectedSeats
    ]);
    
    if (in_array($seatId, $this->reservedSeats)) {
        session()->flash('warning', 'Kursi sudah direservasi.');
        \Log::warning('Seat already reserved', ['seat_id' => $seatId]);
        return;
    }

    if (in_array($seatId, $this->selectedSeats)) {
        $this->selectedSeats = array_filter($this->selectedSeats, fn($id) => $id !== $seatId);
        \Log::info('Seat deselected', ['seat_id' => $seatId]);
    } else {
        $this->selectedSeats[] = $seatId;
        \Log::info('Seat selected', ['seat_id' => $seatId]);
    }

    \Log::info('Seat selection result', [
        'selected_seats_after' => $this->selectedSeats,
        'selected_count' => count($this->selectedSeats)
    ]);

    $this->updateTotals();
    $this->dispatch('seatSelectionUpdated', $this->selectedSeats);
}

    public function toggleTableSelection($tableId)
{
    // FIXED: Convert to integer for consistency
    $tableId = (int) $tableId;
    
    \Log::info('Toggle table selection', [
        'table_id' => $tableId,
        'table_id_type' => gettype($tableId),
        'reserved_tables' => $this->reservedTables,
        'reserved_tables_types' => array_map('gettype', $this->reservedTables),
        'is_reserved' => in_array($tableId, $this->reservedTables),
        'is_reserved_strict' => in_array($tableId, $this->reservedTables, true),
        'selected_tables_before' => $this->selectedTables
    ]);
    
    if (in_array($tableId, $this->reservedTables)) {
        session()->flash('warning', 'Meja sudah direservasi.');
        \Log::warning('Table already reserved', ['table_id' => $tableId]);
        return;
    }

    if (in_array($tableId, $this->selectedTables)) {
        $this->selectedTables = array_filter($this->selectedTables, fn($id) => $id !== $tableId);
        \Log::info('Table deselected', ['table_id' => $tableId]);
    } else {
        // For per_table mode, usually only one table at a time
        $this->selectedTables = [$tableId];
        \Log::info('Table selected', ['table_id' => $tableId]);
    }

    \Log::info('Table selection result', [
        'selected_tables_after' => $this->selectedTables,
        'selected_count' => count($this->selectedTables)
    ]);

    $this->updateTotals();
    $this->dispatch('tableSelectionUpdated', $this->selectedTables);
}

   public function updateTotals()
{
    $this->totalAmount = 0;
    $this->totalSeats = 0;

    \Log::info('Updating totals', [
        'selling_mode' => $this->sellingMode,
        'selected_seats' => $this->selectedSeats,
        'selected_tables' => $this->selectedTables
    ]);

    if ($this->sellingMode === 'per_table') {
        foreach ($this->selectedTables as $tableId) {
            // FIXED: Try to get from layout config first, then database
            $table = null;
            
            // First try to get from layout config
            if (isset($this->selectedLayout['layout_config']['tables'])) {
                $table = collect($this->selectedLayout['layout_config']['tables'])
                    ->firstWhere('id', $tableId);
                
                if ($table) {
                    $this->totalAmount += $table['price'] ?? 0;
                    $this->totalSeats += $table['capacity'] ?? 4;
                    
                    \Log::info('Table total calculated from config', [
                        'table_id' => $tableId,
                        'price' => $table['price'] ?? 0,
                        'capacity' => $table['capacity'] ?? 4
                    ]);
                    continue;
                }
            }
            
            // Fallback to database
            $table = Table::find($tableId);
            if ($table) {
                $this->totalAmount += $table->table_price;
                $this->totalSeats += $table->capacity;
                
                \Log::info('Table total calculated from database', [
                    'table_id' => $tableId,
                    'price' => $table->table_price,
                    'capacity' => $table->capacity
                ]);
            } else {
                \Log::warning('Table not found', ['table_id' => $tableId]);
            }
        }
    } else {
        foreach ($this->selectedSeats as $seatId) {
            // FIXED: Try to get from layout config first, then database
            $seat = null;
            
            // First try to get from layout config
            if (isset($this->selectedLayout['layout_config']['custom_seats'])) {
                $seat = collect($this->selectedLayout['layout_config']['custom_seats'])
                    ->firstWhere('id', $seatId);
                
                if ($seat) {
                    $this->totalAmount += $seat['price'] ?? 0;
                    $this->totalSeats++;
                    
                    \Log::info('Seat total calculated from config', [
                        'seat_id' => $seatId,
                        'price' => $seat['price'] ?? 0
                    ]);
                    continue;
                }
            }
            
            // Fallback to database
            $seat = Seat::find($seatId);
            if ($seat) {
                $this->totalAmount += $seat->seat_price;
                $this->totalSeats++;
                
                \Log::info('Seat total calculated from database', [
                    'seat_id' => $seatId,
                    'price' => $seat->seat_price
                ]);
            } else {
                \Log::warning('Seat not found', ['seat_id' => $seatId]);
            }
        }
    }

    \Log::info('Totals updated', [
        'total_amount' => $this->totalAmount,
        'total_seats' => $this->totalSeats,
        'selling_mode' => $this->sellingMode
    ]);
}

    public function clearSelections()
    {
        $this->selectedSeats = [];
        $this->selectedTables = [];
        $this->updateTotals();
        $this->showBookingSummary = false;
    }

    public function proceedToBooking()
    {
        \Log::info('📝 Proceeding to booking', [
            'selling_mode' => $this->sellingMode,
            'selected_seats' => $this->selectedSeats,
            'selected_tables' => $this->selectedTables,
            'selected_seats_count' => count($this->selectedSeats),
            'selected_tables_count' => count($this->selectedTables)
        ]);

        if ($this->sellingMode === 'per_seat' && empty($this->selectedSeats)) {
            session()->flash('error', 'Silakan pilih minimal satu kursi.');
            return;
        }

        if ($this->sellingMode === 'per_table' && empty($this->selectedTables)) {
            session()->flash('error', 'Silakan pilih minimal satu meja.');
            return;
        }

        $this->showBookingSummary = true;

        \Log::info('✅ Booking summary shown', [
            'showBookingSummary' => $this->showBookingSummary,
            'still_selected_tables' => $this->selectedTables
        ]);
    }

    public function processReservation()
    {
        // Debug: Log awal proses
        \Log::info('🔄 Processing reservation started', [
            'user_id' => auth()->id(),
            'selected_seats' => $this->selectedSeats,
            'selected_tables' => $this->selectedTables,
            'selling_mode' => $this->sellingMode
        ]);

        try {
            // FIXED: Use dynamic validation rules
            $this->validate($this->getRules(), $this->getMessages());
            \Log::info('✅ Validation passed');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Validation failed', [
                'errors' => $e->errors(),
                'selling_mode' => $this->sellingMode,
                'form_data' => [
                    'customerName' => $this->customerName,
                    'customerEmail' => $this->customerEmail,
                    'customerPhone' => $this->customerPhone,
                    'selectedSeats' => $this->selectedSeats,
                    'selectedTables' => $this->selectedTables
                ]
            ]);
            
            // Show validation errors to user
            session()->flash('error', 'Data tidak valid: ' . collect($e->errors())->flatten()->implode(', '));
            return;
        }

        if ($this->isProcessing) {
            \Log::warning('⚠️ Already processing, skipping...');
            return;
        }

        $this->isProcessing = true;

        DB::beginTransaction();

        try {
            // Check if seats/tables are still available
            $this->loadReservedItems();
            
            if ($this->sellingMode === 'per_seat') {
                if (empty($this->selectedSeats)) {
                    throw new Exception('Tidak ada kursi yang dipilih');
                }
                
                $conflictSeats = array_intersect($this->selectedSeats, $this->reservedSeats);
                if (!empty($conflictSeats)) {
                    throw new Exception('Kursi ' . implode(', ', $conflictSeats) . ' sudah tidak tersedia. Silakan refresh halaman.');
                }
            } else {
                if (empty($this->selectedTables)) {
                    throw new Exception('Tidak ada meja yang dipilih');
                }
                
                $conflictTables = array_intersect($this->selectedTables, $this->reservedTables);
                if (!empty($conflictTables)) {
                    throw new Exception('Meja ' . implode(', ', $conflictTables) . ' sudah tidak tersedia. Silakan refresh halaman.');
                }
            }

            \Log::info('✅ Availability check passed');

            // Create reservation
            $reservationData = [
                'reservation_code' => $this->generateReservationCode(),
                'user_id' => Auth::id(),
                'event_id' => $this->event->event_id,
                'reservation_status' => 'pending',
                'total_amount' => $this->totalAmount,
                'total_seats' => $this->totalSeats,
                'reservation_date' => now(),
                'expire_date' => now()->addMinutes(30), // 30 minutes to complete payment
            ];

            \Log::info('📝 Creating reservation with data', $reservationData);

            $reservation = Reservation::create($reservationData);

            \Log::info('✅ Reservation created', [
                'reservation_id' => $reservation->reservation_id,
                'reservation_code' => $reservation->reservation_code
            ]);

            // Create reservation seats
            \Log::info('🔧 About to create reservation seats/tables', [
                'selling_mode' => $this->sellingMode,
                'selling_mode_type' => gettype($this->sellingMode),
                'selected_seats' => $this->selectedSeats,
                'selected_seats_count' => count($this->selectedSeats),
                'selected_tables' => $this->selectedTables,
                'selected_tables_count' => count($this->selectedTables),
                'comparison_per_seat' => ($this->sellingMode === 'per_seat'),
                'comparison_per_table' => ($this->sellingMode === 'per_table')
            ]);

            if ($this->sellingMode === 'per_seat') {
                \Log::info('🪑 Calling createSeatReservations');
                $this->createSeatReservations($reservation);
            } elseif ($this->sellingMode === 'per_table') {
                \Log::info('🍽️ Calling createTableReservations');
                $this->createTableReservations($reservation);
            } else {
                \Log::error('❌ Unknown selling mode', [
                    'selling_mode' => $this->sellingMode,
                    'selling_mode_raw' => var_export($this->sellingMode, true)
                ]);
            }

            DB::commit();

            \Log::info('✅ Database transaction committed successfully');

            $this->reservationCode = $reservation->reservation_code;
            $this->reservationSuccess = true;
            $this->clearSelections();

            // Verify data was actually saved
            $savedReservation = Reservation::find($reservation->reservation_id);
            $reservationSeatsCount = ReservationSeat::where('reservation_id', $reservation->reservation_id)->count();
            
            \Log::info('🔍 Post-commit verification', [
                'reservation_exists' => !is_null($savedReservation),
                'reservation_seats_count' => $reservationSeatsCount,
                'selected_tables_count' => count($this->selectedTables)
            ]);

            \Log::info('🎉 Reservation completed successfully', [
                'reservation_id' => $reservation->reservation_id,
                'reservation_code' => $reservation->reservation_code,
                'user_id' => Auth::id(),
                'total_amount' => $this->totalAmount
            ]);

            session()->flash('success', 'Reservasi berhasil! Kode reservasi: ' . $this->reservationCode);

        } catch (Exception $e) {
            DB::rollBack();
            
            \Log::error('❌ Reservation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'form_data' => [
                    'customerName' => $this->customerName,
                    'customerEmail' => $this->customerEmail,
                    'customerPhone' => $this->customerPhone,
                    'selectedSeats' => $this->selectedSeats,
                    'selectedTables' => $this->selectedTables,
                    'totalAmount' => $this->totalAmount
                ]
            ]);
            
            session()->flash('error', 'Gagal membuat reservasi: ' . $e->getMessage());
            
        } finally {
            $this->isProcessing = false;
        }
    }

    
    private function createSeatReservations(Reservation $reservation)
    {
        foreach ($this->selectedSeats as $seatId) {
            $seat = Seat::find($seatId);
            
            if ($seat) {
                // Create reservation seat record
                ReservationSeat::create([
                    'reservation_id' => $reservation->reservation_id,
                    'seat_id' => $seat->seat_id,
                    'seat_price' => $seat->seat_price,
                ]);

                // FIXED: Explicitly update is_available to 0
                $seat->update([
                    'is_available' => 0  // atau false jika boolean
                ]);
                
                \Log::info('Seat reserved', [
                    'seat_id' => $seat->seat_id,
                    'seat_number' => $seat->seat_number,
                    'is_available_updated' => true
                ]);
            }
        }
    }

    private function createTableReservations(Reservation $reservation)
    {
        foreach ($this->selectedTables as $tableId) {
            $table = Table::find($tableId);
            
            if ($table) {
                // Create reservation record for table
                ReservationSeat::create([
                    'reservation_id' => $reservation->reservation_id,
                    'table_id' => $table->table_id,
                    'seat_price' => $table->table_price,
                ]);
                
                // FIXED: Explicitly update is_available to 0
                $table->update([
                    'is_available' => 0  // atau false jika boolean
                ]);
                
                \Log::info('Table reserved', [
                    'table_id' => $table->table_id,
                    'table_number' => $table->table_number,
                    'is_available_updated' => true
                ]);
            }
        }
    }

    private function generateReservationCode()
    {
        do {
            $code = 'RSV' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (Reservation::where('reservation_code', $code)->exists());

        return $code;
    }

    public function goToPayment()
    {
        return $this->redirect(route('app.payment', ['code' => $this->reservationCode]));
    }

    public function render()
    {
        return view('livewire.app.seat-reservation');
    }
}