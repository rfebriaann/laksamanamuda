<?php
          namespace App\Livewire\App;

          use App\Models\Event;
          use App\Models\SeatLayout;
          use Livewire\Component;
          use Livewire\Attributes\Title;
          use Livewire\Attributes\Layout;
          use Livewire\WithPagination;
          use Illuminate\Support\Facades\DB;
          use Carbon\Carbon;

          #[Title('Daftar Event - Reservasi Tiket')]
          #[Layout('layouts.app')]
          class ReservationIndex extends Component
          {
          use WithPagination;

          // Filter properties
          public $search = '';
          public $filterDate = '';
          public $filterVenue = '';
          public $filterCategory = '';
          public $sortBy = 'event_date';
          public $sortDirection = 'asc';

          // UI properties
          public $showFilters = false;

          protected $queryString = [
          'search' => ['except' => ''],
          'filterDate' => ['except' => ''],
          'filterVenue' => ['except' => ''],
          'filterCategory' => ['except' => ''],
          ];

          public function mount()
          {
          // Set default filter untuk events yang upcoming
          if (empty($this->filterDate)) {
                    $this->filterDate = '';
          }
          }

          public function updatedSearch()
          {
          $this->resetPage();
          }

          public function updatedFilterDate()
          {
          $this->resetPage();
          }

          public function updatedFilterVenue()
          {
          $this->resetPage();
          }

          public function updatedFilterCategory()
          {
          $this->resetPage();
          }

          public function clearFilters()
          {
          $this->search = '';
          $this->filterDate = '';
          $this->filterVenue = '';
          $this->filterCategory = '';
          $this->resetPage();
          }

          public function sortBy($field)
          {
          if ($this->sortBy === $field) {
                    $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
          } else {
                    $this->sortBy = $field;
                    $this->sortDirection = 'asc';
          }
          $this->resetPage();
          }

          public function toggleFilters()
          {
          $this->showFilters = !$this->showFilters;
          }

          public function goToReservation($eventId)
          {
          return $this->redirect(route('app.reservation', $eventId));
          }

          private function getEvents()
          {
          $query = Event::query()
                    ->with(['seatLayouts' => function($query) {
                    $query->select('layout_id', 'event_id', 'layout_name', 'layout_config', 'selling_mode');
                    }])
                    ->where('event_date', '>=', now()->toDateString()); // Only upcoming events
                    // ->where('event_status', 'active'); // REMOVED - kolom tidak ada

          // Apply search filter
          if (!empty($this->search)) {
                    $query->where(function($q) {
                    $q->where('event_name', 'like', '%' . $this->search . '%')
                    ->orWhere('event_description', 'like', '%' . $this->search . '%')
                    ->orWhere('venue', 'like', '%' . $this->search . '%');
                    });
          }

          // Apply date filter
          if (!empty($this->filterDate)) {
                    $query->whereDate('event_date', $this->filterDate);
          }

          // Apply venue filter
          if (!empty($this->filterVenue)) {
                    $query->where('venue', 'like', '%' . $this->filterVenue . '%');
          }

          // Apply category filter - cek dulu apakah kolom ada
          if (!empty($this->filterCategory)) {
                    // Cek apakah kolom event_category ada, jika tidak gunakan kolom lain atau skip
                    if (\Schema::hasColumn('events', 'event_category')) {
                    $query->where('event_category', $this->filterCategory);
                    } elseif (\Schema::hasColumn('events', 'category')) {
                    $query->where('category', $this->filterCategory);
                    }
                    // Jika tidak ada kolom kategori, filter diabaikan
          }

          // Apply sorting
          $query->orderBy($this->sortBy, $this->sortDirection);

          return $query->paginate(12);
          }

          private function getAvailableVenues()
          {
          return Event::where('event_date', '>=', now()->toDateString())
                    // ->where('event_status', 'active') // REMOVED
                    ->distinct()
                    ->pluck('venue_name')
                    ->filter()
                    ->sort()
                    ->values();
          }

          private function getAvailableCategories()
          {
          // Cek kolom kategori yang tersedia
          if (\Schema::hasColumn('events', 'event_category')) {
                    $categoryColumn = 'event_category';
          } elseif (\Schema::hasColumn('events', 'category')) {
                    $categoryColumn = 'category';
          } else {
                    // Jika tidak ada kolom kategori, return empty collection
                    return collect([]);
          }

          return Event::where('event_date', '>=', now()->toDateString())
                    // ->where('event_status', 'active') // REMOVED
                    ->distinct()
                    ->pluck($categoryColumn)
                    ->filter()
                    ->sort()
                    ->values();
          }

          private function getEventStats($event)
          {
          $stats = [
                    'total_capacity' => 0,
                    'available_seats' => 0,
                    'reserved_seats' => 0,
                    'min_price' => 0,
                    'max_price' => 0,
                    'layouts_count' => 0,
                    'selling_modes' => []
          ];

          if ($event->seatLayouts->isEmpty()) {
                    return $stats;
          }

          $stats['layouts_count'] = $event->seatLayouts->count();

          foreach ($event->seatLayouts as $layout) {
                    $config = $layout->layout_config;
                    $sellingMode = $config['selling_mode'] ?? 'per_seat';
                    
                    if (!in_array($sellingMode, $stats['selling_modes'])) {
                    $stats['selling_modes'][] = $sellingMode;
                    }

                    if ($sellingMode === 'per_table') {
                    $tables = $config['tables'] ?? [];
                    $capacity = collect($tables)->sum('capacity');
                    $stats['total_capacity'] += $capacity;
                    
                    $tablePrice = $config['table_price'] ?? 500000;
                    $stats['min_price'] = $stats['min_price'] == 0 ? $tablePrice : min($stats['min_price'], $tablePrice);
                    $stats['max_price'] = max($stats['max_price'], $tablePrice);
                    } else {
                    $seats = $config['custom_seats'] ?? [];
                    $stats['total_capacity'] += count($seats);
                    
                    $regularPrice = $config['regular_price'] ?? 150000;
                    $vipPrice = $config['vip_price'] ?? 300000;
                    
                    $stats['min_price'] = $stats['min_price'] == 0 ? $regularPrice : min($stats['min_price'], $regularPrice);
                    $stats['max_price'] = max($stats['max_price'], $vipPrice);
                    }
          }

          $reservedCount = DB::table('reservation_seats as rs')
                    ->join('reservations as r', 'rs.reservation_id', '=', 'r.reservation_id')
                    ->where('r.event_id', $event->event_id)
                    ->whereIn('r.reservation_status', ['pending', 'confirmed'])
                    ->where('r.expire_date', '>', now())
                    ->count();

          $stats['reserved_seats'] = $reservedCount;
          $stats['available_seats'] = max(0, $stats['total_capacity'] - $reservedCount);

          return $stats;
          }

          public function render()
          {
          $events = $this->getEvents();
          $venues = $this->getAvailableVenues();
          $categories = $this->getAvailableCategories();

          // Add stats to each event
          $events->getCollection()->transform(function ($event) {
                    $event->stats = $this->getEventStats($event);
                    return $event;
          });

          return view('livewire.app.reservation-index', [
                    'events' => $events,
                    'venues' => $venues,
                    'categories' => $categories,
          ]);
          }
          }