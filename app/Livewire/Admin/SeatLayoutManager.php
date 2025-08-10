<?php
// wifi sebiji bawah
// username : desember
// pass : simonelli
namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use App\Models\Table;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Livewire\Attributes\Layout;

class SeatLayoutManager extends Component
{
    #[On('updateSeats')]
    public $listeners = ['updateSeats'];
    public Event $event;
    public $seatLayouts = [];
    
    #[Rule('required|string|max:255')]
    public $layout_name = '';
    
    public $rows;
    public $columns;
    public $vip_rows = [];
    public $vip_price = 300000;
    public $regular_price = 150000;
    public $custom_seats = [];
    
    // Mode penjualan: per_seat atau per_table
    public $selling_mode = 'per_seat';
    public $tables = [];
    public $table_price = 500000;
    public $table_capacity = 4;
    
    public $showLayoutModal = false;
    public $editingLayoutId = null;
    
    // Debug properties
    public $debug_mode = false;
    public $debug_info = [];

    public function updateSeats($seats)
    {
        $this->debugLog('updateSeats', [
            'input_seats' => $seats,
            'seats_count' => is_array($seats) ? count($seats) : 'not_array',
            'seats_data_sample' => is_array($seats) && count($seats) > 0 ? array_slice($seats, 0, 3) : 'empty_or_invalid'
        ]);
        
        if (is_array($seats)) {
            $this->custom_seats = $seats;
            $this->debugLog('updateSeats_success', [
                'updated_seats_count' => count($this->custom_seats)
            ]);
        } else {
            $this->debugLog('updateSeats_error', [
                'error' => 'Invalid seats data - not an array',
                'received_type' => gettype($seats),
                'received_value' => $seats
            ]);
        }
    }

    public function mount(Event $event)
    {
        $this->columns = 20;
        $this->rows = 10;
        $this->event = $event;
        
        $this->debugLog('component_mount', [
            'event_id' => $event->event_id ?? $event->id ?? 'no_id',
            'event_name' => $event->event_name ?? $event->name ?? 'no_name',
            'event_object' => [
                'id_field' => $event->id ?? 'missing',
                'event_id_field' => $event->event_id ?? 'missing',
                'primary_key' => $event->getKeyName(),
                'attributes' => array_keys($event->getAttributes()),
                'fillable' => $event->getFillable()
            ]
        ]);
        
        $this->loadSeatLayouts();
        $this->initializeDebugMode();
    }

    private function initializeDebugMode()
    {
        // Enable debug mode if in local environment
        $this->debug_mode = config('app.debug', false) || app()->environment('local');
        
        if ($this->debug_mode) {
            $this->debugLog('debug_mode_enabled', [
                'environment' => app()->environment(),
                'debug_config' => config('app.debug'),
                'log_level' => config('logging.level'),
                'database_connection' => config('database.default')
            ]);
        }
    }

    private function debugLog($action, $data = [])
    {
        $logData = [
            'component' => 'SeatLayoutManager',
            'action' => $action,
            'timestamp' => now()->toDateTimeString(),
            'data' => $data,
            'memory_usage' => memory_get_usage(true),
            'session_id' => session()->getId()
        ];
        
        Log::info("SeatLayoutManager Debug: {$action}", $logData);
        
        // Store in component for real-time debugging
        $this->debug_info[] = $logData;
        
        // Keep only last 50 debug entries
        if (count($this->debug_info) > 50) {
            $this->debug_info = array_slice($this->debug_info, -50);
        }
    }

    public function render()
    {
        return view('livewire.admin.seat-layout-manager');
    }

    public function loadSeatLayouts()
    {
        try {
            $this->debugLog('load_layouts_start', [
                'event_primary_key' => $this->event->getKeyName(),
                'event_id_value' => $this->event->getKey()
            ]);
            
            $this->seatLayouts = $this->event->seatLayouts()
                ->withCount('seats')
                ->get()
                ->toArray();
                
            $this->debugLog('load_layouts_success', [
                'layouts_count' => count($this->seatLayouts),
                'layouts_sample' => count($this->seatLayouts) > 0 ? array_slice($this->seatLayouts, 0, 2) : 'none'
            ]);
            
        } catch (Exception $e) {
            $this->debugLog('load_layouts_error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

public function editLayout($layoutId)
{
    try {
        $this->debugLog('edit_layout_start', [
            'layout_id' => $layoutId
        ]);

        $layout = SeatLayout::findOrFail($layoutId);
        $config = $layout->layout_config;

        // Set form data
        $this->editingLayoutId = $layoutId;
        $this->layout_name = $layout->layout_name;
        $this->selling_mode = $config['selling_mode'] ?? 'per_seat';
        
        // FIXED: Load and transform seats and tables data properly
        $this->custom_seats = $this->transformSeatsForEdit($config['custom_seats'] ?? []);
        $this->tables = $this->transformTablesForEdit($config['tables'] ?? []);
        
        // Load pricing
        $this->vip_price = $config['vip_price'] ?? 300000;
        $this->regular_price = $config['regular_price'] ?? 150000;
        $this->table_price = $config['table_price'] ?? 500000;
        $this->table_capacity = $config['table_capacity'] ?? 4;

        $this->debugLog('edit_layout_data_loaded', [
            'layout_id' => $layoutId,
            'selling_mode' => $this->selling_mode,
            'seats_count' => count($this->custom_seats),
            'tables_count' => count($this->tables),
            'seats_sample' => array_slice($this->custom_seats, 0, 2),
            'tables_sample' => array_slice($this->tables, 0, 2)
        ]);

        // Open modal
        $this->showLayoutModal = true;

        // FIXED: Dispatch event to trigger JavaScript data reload
        $this->dispatch('layout-data-loaded', [
            'selling_mode' => $this->selling_mode,
            'custom_seats' => $this->custom_seats,
            'tables' => $this->tables,
            'layout_id' => $layoutId
        ]);

        $this->debugLog('edit_layout_success', [
            'layout_id' => $layoutId,
            'selling_mode' => $this->selling_mode,
            'seats_count' => count($this->custom_seats),
            'tables_count' => count($this->tables)
        ]);

    } catch (Exception $e) {
        $this->debugLog('edit_layout_error', [
            'layout_id' => $layoutId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('error', 'Gagal memuat layout: ' . $e->getMessage());
    }
}

    public function saveLayout()
    {
        $this->debugLog('save_layout_start', [
            'layout_name' => $this->layout_name,
            'selling_mode' => $this->selling_mode,
            'custom_seats_count' => count($this->custom_seats),
            'custom_seats_sample' => array_slice($this->custom_seats, 0, 3),
            'tables_count' => count($this->tables),
            'vip_price' => $this->vip_price,
            'regular_price' => $this->regular_price,
            'editing_layout_id' => $this->editingLayoutId,
            'request_data' => request()->all()
        ]);

        // Enhanced validation with debugging
        try {
            $this->validate();
            $this->debugLog('validation_passed', [
                'validated_fields' => [
                    'layout_name' => $this->layout_name,
                    'custom_seats_count' => count($this->custom_seats)
                ]
            ]);
        } catch (Exception $e) {
            $this->debugLog('validation_failed', [
                'error' => $e->getMessage(),
                'validation_errors' => $this->getErrorBag()->toArray(),
                'current_data' => [
                    'layout_name' => $this->layout_name,
                    'custom_seats' => $this->custom_seats,
                    'selling_mode' => $this->selling_mode
                ]
            ]);
            return;
        }

        // Check data based on selling mode
        if ($this->selling_mode === 'per_table') {
            if (empty($this->tables)) {
                $this->debugLog('no_tables_error', [
                    'tables' => $this->tables,
                    'selling_mode' => $this->selling_mode
                ]);
                // $this->generateTables($layout);
                $this->addError('tables', 'Layout harus memiliki minimal satu meja.');
                return;
            }
        } else {
            if (empty($this->custom_seats)) {
                // dd();
                $this->debugLog('no_seats_error', [
                    'custom_seats' => $this->custom_seats,
                    'selling_mode' => $this->selling_mode
                ]);
                // $this->generateCustomSeats($layout);
                $this->addError('custom_seats', 'Layout harus memiliki minimal satu kursi.');
                return;
            }
        }

        // Event ID debugging
        
        $eventId = $this->getEventId();
        $this->debugLog('event_id_resolution', [
            'event_object_key' => $this->event->getKey(),
            'event_id_property' => $this->event->event_id ?? 'not_set',
            'event_id_attribute' => $this->event->getAttribute('event_id') ?? 'not_set',
            'resolved_event_id' => $eventId,
            'event_table' => $this->event->getTable(),
            'event_primary_key' => $this->event->getKeyName()
        ]);

        // Database connection debugging
        $this->debugDatabaseConnection();

        $layoutData = [
            'event_id' => $eventId,
            'layout_name' => $this->layout_name,
            'layout_config' => [
                'selling_mode' => $this->selling_mode,
                'custom_seats' => $this->custom_seats,
                'tables' => $this->tables,
                'vip_price' => $this->vip_price,
                'regular_price' => $this->regular_price,
                'table_price' => $this->table_price,
                'table_capacity' => $this->table_capacity,
                'created_with' => 'interactive_designer',
                'version' => '2.1',
                'debug_info' => [
                    'created_at' => now(),
                    'user_id' => auth()->id(),
                    'ip_address' => request()->ip()
                ]
            ],
        ];

        // dd($layoutData);

        $this->debugLog('layout_data_prepared', [
            'layout_data' => $layoutData,
            'layout_data_size' => strlen(json_encode($layoutData))
        ]);

        // Table structure debugging
        $this->debugTableStructure();

        DB::beginTransaction();
        
        try {
            if ($this->editingLayoutId) {
                $this->updateExistingLayout($layoutData);
            } else {
                $this->createNewLayout($layoutData);
            }
            
            DB::commit();
            $this->debugLog('transaction_committed', [
                'editing' => !!$this->editingLayoutId
            ]);
            
            $this->closeModal();
            $this->loadSeatLayouts();
            
        } catch (Exception $e) {
            DB::rollBack();
            $this->debugLog('transaction_failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'sql_state' => $e->getCode(),
                'editing' => !!$this->editingLayoutId
            ]);
            
            session()->flash('error', 'Gagal menyimpan layout: ' . $e->getMessage());
            
            // Additional error context
            if (str_contains($e->getMessage(), 'foreign key')) {
                $this->debugLog('foreign_key_constraint_error', [
                    'event_id_used' => $layoutData['event_id'],
                    'event_exists' => Event::where('id', $layoutData['event_id'])->exists(),
                    'events_table_check' => $this->checkEventsTable()
                ]);
            }
        }
    }

    private function getEventId()
    {
        // Multiple ways to get event ID
        $eventId = null;
        
        if (isset($this->event->event_id)) {
            $eventId = $this->event->event_id;
        } elseif (isset($this->event->id)) {
            $eventId = $this->event->id;
        } else {
            $eventId = $this->event->getKey();
        }
        
        return $eventId;
    }

    private function debugDatabaseConnection()
    {
        try {
            $connection = DB::connection();
            $this->debugLog('database_connection_info', [
                'connection_name' => $connection->getName(),
                'database_name' => $connection->getDatabaseName(),
                'table_prefix' => $connection->getTablePrefix(),
                'driver_name' => $connection->getDriverName(),
                'pdo_available' => $connection->getPdo() !== null,
                'query_log_enabled' => $connection->logging()
            ]);
            
            // Test database connectivity
            $testResult = DB::select('SELECT 1 as test');
            $this->debugLog('database_connectivity_test', [
                'test_result' => $testResult,
                'connection_success' => !empty($testResult)
            ]);
            
        } catch (Exception $e) {
            $this->debugLog('database_connection_error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    private function debugTableStructure()
    {
        try {
            // Check SeatLayout table structure
            $seatLayoutColumns = DB::select("DESCRIBE seat_layouts");
            $this->debugLog('seat_layout_table_structure', [
                'columns' => $seatLayoutColumns,
                'table_exists' => !empty($seatLayoutColumns)
            ]);
            
            // Check Seat table structure
            $seatColumns = DB::select("DESCRIBE seats");
            $this->debugLog('seat_table_structure', [
                'columns' => $seatColumns,
                'table_exists' => !empty($seatColumns)
            ]);
            
            // Check Events table
            $eventColumns = DB::select("DESCRIBE events");
            $this->debugLog('event_table_structure', [
                'columns' => $eventColumns,
                'table_exists' => !empty($eventColumns)
            ]);
            
        } catch (Exception $e) {
            $this->debugLog('table_structure_check_failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function checkEventsTable()
    {
        try {
            $eventCount = Event::count();
            $currentEvent = Event::find($this->getEventId());
            
            return [
                'total_events' => $eventCount,
                'current_event_exists' => !is_null($currentEvent),
                'current_event_data' => $currentEvent ? $currentEvent->toArray() : null
            ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    private function updateExistingLayout($layoutData)
    {
        $layout = SeatLayout::findOrFail($this->editingLayoutId);
        $this->debugLog('update_layout_start', [
            'layout_id' => $this->editingLayoutId,
            'old_data' => $layout->toArray(),
            'new_data' => $layoutData
        ]);
        
        $layout->update($layoutData);
        
        $this->debugLog('layout_updated', [
            'layout_id' => $layout->getKey(),
            'updated_data' => $layout->fresh()->toArray()
        ]);
        
        // Delete existing seats
        $deletedSeats = $layout->seats()->count();
        $layout->seats()->delete();
        
        $this->debugLog('existing_seats_deleted', [
            'deleted_count' => $deletedSeats
        ]);
        
        // Generate new seats/tables
        if ($this->selling_mode === 'per_table') {
            $this->generateTables($layout);
        } else {
            $this->generateCustomSeats($layout);
        }
        
        session()->flash('message', 'Layout berhasil diperbarui!');
    }

    private function createNewLayout($layoutData)
    {
        $this->debugLog('create_layout_start', [
            'layout_data' => $layoutData
        ]);
        
        $layout = SeatLayout::create($layoutData);
        
        $this->debugLog('layout_created', [
            'layout_id' => $layout->getKey(),
            'created_data' => $layout->toArray()
        ]);
        
        // Generate seats/tables
        if ($this->selling_mode === 'per_table') {
            $this->generateTables($layout);
        } else {
            $this->generateCustomSeats($layout);
        }
        
        session()->flash('message', 'Layout berhasil dibuat!');
    }

    private function generateCustomSeats($layout)
    {
        $this->debugLog('generate_seats_start', [
            'layout_id' => $layout->getKey(),
            'seats_to_create' => count($this->custom_seats),
            'selling_mode' => $this->selling_mode
        ]);

        $createdSeats = 0;
        $errors = [];

        foreach ($this->custom_seats as $index => $seatData) {
            try {
                $seatNumber = $this->generateSeatNumber($seatData);
                $seatRow = $this->generateSeatRow($seatData);
                
                $seatDataToCreate = [
                    'layout_id' => $layout->getKey(),
                    'seat_number' => $seatNumber,
                    'seat_row' => $seatRow,
                    'seat_type' => $seatData['type'] ?? 'Regular',
                    'seat_price' => $seatData['type'] === 'VIP' ? $this->vip_price : $this->regular_price,
                    'is_available' => true,
                    'position_x' => $seatData['x'] ?? 0,
                    'position_y' => $seatData['y'] ?? 0,
                ];

                $this->debugLog('creating_seat', [
                    'seat_index' => $index,
                    'seat_data' => $seatDataToCreate,
                    'original_seat_data' => $seatData
                ]);

                $seat = Seat::create($seatDataToCreate);
                
                $this->debugLog('seat_created', [
                    'seat_id' => $seat->getKey(),
                    'seat_number' => $seat->seat_number,
                    'created_data' => $seat->toArray()
                ]);
                
                $createdSeats++;
                
            } catch (Exception $e) {
                $error = [
                    'seat_index' => $index,
                    'seat_data' => $seatData,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ];
                $errors[] = $error;
                
                $this->debugLog('seat_creation_failed', $error);
            }
        }

        $this->debugLog('generate_seats_completed', [
            'total_seats_processed' => count($this->custom_seats),
            'successfully_created' => $createdSeats,
            'errors_count' => count($errors),
            'errors' => $errors
        ]);

        if (count($errors) > 0) {
            throw new Exception('Gagal membuat ' . count($errors) . ' kursi. Lihat log untuk detail.');
        }
    }

    private function generateTables($layout)
    {
    // Log informasi awal
    \Log::info('🍽️ Generating tables for layout', [
        'layout_id' => $layout->getKey() ?? $layout->layout_id,
        'tables_count' => count($this->tables),
        'table_capacity' => $this->table_capacity,
        'table_price' => $this->table_price
    ]);

    // Debug: tampilkan contoh data meja
    if (count($this->tables) > 0) {
        \Log::debug('📊 Contoh data meja pertama:', [
            'table_data' => $this->tables[0]
        ]);
    } else {
        \Log::warning('⚠️ Tidak ada data meja untuk dibuat!');
        return;
    }
    $createdTables = 0;
    $insertData = [];
    $now = now();

    // Membuat data untuk batch insert
    foreach ($this->tables as $index => $tableData) {
        try {
            // Pastikan kapasitas meja valid
            $capacity = isset($tableData['capacity']) ? (int)$tableData['capacity'] : $this->table_capacity;
            if ($capacity < 2) $capacity = 2; // Minimal 2 orang
            if ($capacity > 12) $capacity = 12; // Maksimal 12 orang
            
            // Pastikan shape valid
            $shape = $tableData['shape'] ?? 'square';
            if (!in_array($shape, ['square', 'circle', 'rectangle', 'diamond'])) {
                $shape = 'square'; // Default ke square jika tidak valid
            }
            
            // Siapkan data untuk tabel (model Seat)
            $tableRecord = [
                'layout_id' => $layout->getKey() ?? $layout->layout_id,
                'table_number' => $tableData['number'] ?? ('T' . ($index + 1)),
                'capacity' => $capacity,
                'table_price' => (float) $this->table_price, // Menggunakan seat_type 'TABLE' untuk membedakan dengan kursi
                'position_x' => (int) ($tableData['x'] ?? 0),
                'position_y' => (int) ($tableData['y'] ?? 0),
                'width' => $tableData['width'] ?? 120,
                'height' => $tableData['width'] ?? 120,
                'is_available' => true,
                'table_metadata' => json_encode([
                    'table' => true,
                    'capacity' => $capacity,
                    'shape' => $shape,
                    'width' => $tableData['width'] ?? 120,
                    'height' => $tableData['height'] ?? 120
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ];
            // dd($tableRecord);
            $insertData[] = $tableRecord;
            
            \Log::info('✅ Table data prepared', [
                'index' => $index,
                'table_number' => $tableRecord['table_number'],
                'position' => "({$tableRecord['position_x']}, {$tableRecord['position_y']})",
                'capacity' => $capacity,
                'shape' => $shape
            ]);

            //  $tableSeat = Table::create($tableRecord);

             $this->debugLog('table_created', [
                'seat_id' => $tableSeat->getKey(),
                'tableSeat_number' => $tableSeat->table_number,
                'created_data' => $tableSeat->toArray()
            ]);
            $createdTables++;
        } catch (\Exception $e) {
            \Log::error('❌ Error preparing table data: ' . $e->getMessage(), [
                'index' => $index,
                'table_data' => $tableData,
                'trace' => $e->getTraceAsString()
            ]);
            continue;
        }
    }

    // Batch insert tables jika ada data
    if (!empty($insertData)) {
        try {
            \DB::table('tables')->insert($insertData);
            \Log::info('✅ Tables inserted successfully', ['count' => count($insertData)]);
        } catch (\Exception $e) {
            \Log::error('❌ Error inserting tables: ' . $e->getMessage(), [
                'error_details' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    } else {
        \Log::warning('⚠️ No table data to insert');
    }
}

    private function generateSeatNumber($seatData)
    {
        $number = $seatData['number'] ?? str_pad($seatData['id'] ?? 1, 3, '0', STR_PAD_LEFT);
        
        $this->debugLog('generate_seat_number', [
            'original_data' => $seatData,
            'generated_number' => $number
        ]);
        
        return $number;
    }

    private function generateSeatRow($seatData)
    {
        if (isset($seatData['row'])) {
            $row = $seatData['row'];
        } else {
            $y = $seatData['y'] ?? 0;
            $rowIndex = floor($y / 30);
            $row = chr(65 + min($rowIndex, 25)); // A-Z
        }
        
        $this->debugLog('generate_seat_row', [
            'original_data' => $seatData,
            'generated_row' => $row
        ]);
        
        return $row;
    }

    // Debug method to export debug info
    public function exportDebugInfo()
    {
        $debugData = [
            'component_info' => [
                'selling_mode' => $this->selling_mode,
                'seats_count' => count($this->custom_seats),
                'tables_count' => count($this->tables),
                'layout_name' => $this->layout_name,
                'event_id' => $this->getEventId()
            ],
            'debug_log' => $this->debug_info,
            'system_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time')
            ],
            'exported_at' => now()->toISOString()
        ];

        $filename = "seat-layout-debug-" . now()->format('Y-m-d-H-i-s') . '.json';
        
        return response()->json($debugData)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // Method to clear debug info
    public function clearDebugInfo()
    {
        $this->debug_info = [];
        $this->debugLog('debug_info_cleared', [
            'cleared_by' => auth()->user()->name ?? 'unknown'
        ]);
    }

    // Rest of your existing methods...
    public function createLayout()
    {
        $this->resetForm();
        $this->showLayoutModal = true;
        
        $this->debugLog('create_layout_modal_opened', [
            'user_id' => auth()->id(),
            'session_id' => session()->getId()
        ]);
    }

    public function closeModal()
    {
        $this->showLayoutModal = false;
        $this->resetForm();
        
        $this->debugLog('modal_closed', [
            'debug_entries_count' => count($this->debug_info)
        ]);
    }

    private function resetForm()
    {
        $this->editingLayoutId = null;
        $this->layout_name = '';
        $this->vip_price = 300000;
        $this->regular_price = 150000;
        $this->table_price = 500000;
        $this->custom_seats = [];
        $this->tables = [];
        $this->resetErrorBag();
        
        $this->debugLog('form_reset', [
            'reset_fields' => [
                'layout_name', 'custom_seats', 'tables', 'prices'
            ]
        ]);
    }

    protected function rules()
    {
        $rules = [
            'layout_name' => 'required|string|max:255',
            'vip_price' => 'required|numeric|min:0',
            'regular_price' => 'required|numeric|min:0',
            'table_price' => 'required|numeric|min:0',
        ];

        if ($this->selling_mode === 'per_table') {
            $rules['tables'] = 'array|min:1';
            $rules['tables.*.x'] = 'required|numeric|min:0';
            $rules['tables.*.y'] = 'required|numeric|min:0';
            $rules['tables.*.capacity'] = 'required|integer|min:2|max:12';
        } else {
            $rules['custom_seats'] = 'array|min:1';
            $rules['custom_seats.*.id'] = 'required';
            $rules['custom_seats.*.x'] = 'required|numeric|min:0';
            $rules['custom_seats.*.y'] = 'required|numeric|min:0';
            $rules['custom_seats.*.type'] = 'required|in:Regular,VIP';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'layout_name.required' => 'Nama layout harus diisi.',
            'custom_seats.min' => 'Layout harus memiliki minimal satu kursi.',
            'tables.min' => 'Layout harus memiliki minimal satu meja.',
            'custom_seats.*.type.in' => 'Tipe kursi harus Regular atau VIP.',
            'tables.*.capacity.required' => 'Kapasitas meja harus diisi.',
            'tables.*.capacity.min' => 'Kapasitas meja minimal 2 orang.',
            'tables.*.capacity.max' => 'Kapasitas meja maksimal 12 orang.',
        ];
    }


/**
 * Transform seats data for edit mode
 * Ensures all required fields are present and properly formatted
 */
private function transformSeatsForEdit($seats)
{
    if (!is_array($seats)) {
        $this->debugLog('transform_seats_invalid_input', [
            'input' => $seats,
            'type' => gettype($seats)
        ]);
        return [];
    }

    $transformedSeats = [];
    
    foreach ($seats as $index => $seat) {
        try {
            // Ensure seat has all required fields
            $transformedSeat = [
                'id' => $seat['id'] ?? 'seat_' . ($index + 1),
                'x' => (int) ($seat['x'] ?? 0),
                'y' => (int) ($seat['y'] ?? 0),
                'type' => $seat['type'] ?? 'Regular',
                'row' => $seat['row'] ?? $this->generateSeatRowFromY($seat['y'] ?? 0),
                'number' => $seat['number'] ?? ($index + 1),
                'width' => (int) ($seat['width'] ?? 44),
                'height' => (int) ($seat['height'] ?? 44),
                'table_id' => $seat['table_id'] ?? null
            ];

            // Validate seat type
            if (!in_array($transformedSeat['type'], ['Regular', 'VIP'])) {
                $transformedSeat['type'] = 'Regular';
            }

            // Ensure minimum dimensions
            if ($transformedSeat['width'] < 32) $transformedSeat['width'] = 44;
            if ($transformedSeat['height'] < 32) $transformedSeat['height'] = 44;

            $transformedSeats[] = $transformedSeat;
            
            $this->debugLog('seat_transformed', [
                'index' => $index,
                'original' => $seat,
                'transformed' => $transformedSeat
            ]);

        } catch (Exception $e) {
            $this->debugLog('seat_transform_error', [
                'index' => $index,
                'seat' => $seat,
                'error' => $e->getMessage()
            ]);
            // Skip invalid seats
            continue;
        }
    }

    $this->debugLog('seats_transformation_complete', [
        'input_count' => count($seats),
        'output_count' => count($transformedSeats),
        'first_few' => array_slice($transformedSeats, 0, 3)
    ]);

    return $transformedSeats;
}

/**
 * Transform tables data for edit mode
 * Ensures all required fields are present and properly formatted
 */
private function transformTablesForEdit($tables)
{
    if (!is_array($tables)) {
        $this->debugLog('transform_tables_invalid_input', [
            'input' => $tables,
            'type' => gettype($tables)
        ]);
        return [];
    }

    $transformedTables = [];
    
    foreach ($tables as $index => $table) {
        try {
            // Ensure table has all required fields
            $transformedTable = [
                'id' => $table['id'] ?? 'table_' . ($index + 1),
                'x' => (int) ($table['x'] ?? 0),
                'y' => (int) ($table['y'] ?? 0),
                'shape' => $table['shape'] ?? 'square',
                'capacity' => (int) ($table['capacity'] ?? 4),
                'number' => $table['number'] ?? 'T' . ($index + 1),
                'width' => (int) ($table['width'] ?? 120),
                'height' => (int) ($table['height'] ?? 120)
            ];

            // Validate table shape
            if (!in_array($transformedTable['shape'], ['square', 'circle', 'rectangle', 'diamond'])) {
                $transformedTable['shape'] = 'square';
            }

            // Validate capacity
            if ($transformedTable['capacity'] < 2) $transformedTable['capacity'] = 2;
            if ($transformedTable['capacity'] > 12) $transformedTable['capacity'] = 12;

            // Ensure minimum dimensions
            if ($transformedTable['width'] < 80) $transformedTable['width'] = 120;
            if ($transformedTable['height'] < 80) $transformedTable['height'] = 120;

            $transformedTables[] = $transformedTable;
            
            $this->debugLog('table_transformed', [
                'index' => $index,
                'original' => $table,
                'transformed' => $transformedTable
            ]);

        } catch (Exception $e) {
            $this->debugLog('table_transform_error', [
                'index' => $index,
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            // Skip invalid tables
            continue;
        }
    }

    $this->debugLog('tables_transformation_complete', [
        'input_count' => count($tables),
        'output_count' => count($transformedTables),
        'first_few' => array_slice($transformedTables, 0, 3)
    ]);

    return $transformedTables;
}

/**
 * Generate seat row letter from Y position
 */
private function generateSeatRowFromY($y)
{
    $rowIndex = floor($y / 50);
    return chr(65 + min($rowIndex, 25)); // A-Z
}
}