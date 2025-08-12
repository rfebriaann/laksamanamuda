<?php

use App\Livewire\Admin\SeatLayoutManager;
use App\Livewire\App\SeatReservation;
use App\Livewire\App\ReservationIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Logout;

// Route::get('/', function () {
//     return view('welcome');
// });

// Public Routes (Guest Access)
Route::get('/', ReservationIndex::class)->name('index');
Route::prefix('reservation')->name('reservation.')->group(function () {
    // Public event list for reservation
    Route::get('/', ReservationIndex::class)->name('index');
    
    // Public event detail & reservation (redirect to login if needed)
    Route::get('/event/{event}', function($event) {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('intended', route('app.reservation', $event));
        }
        return redirect()->route('app.reservation', $event);
    })->name('event');
});

// Authentication Routes
Route::get('/login', App\Livewire\Auth\Login::class)->name('login')->middleware('guest');
Route::get('/logout', [Logout::class, 'logout'])->name('logout');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    // Admin Routes (Admin & Super Admin only)
    Route::middleware(['verified', 'role:admin|superadmin'])->group(function () {
        Route::get('/event', App\Livewire\Admin\EventManagement::class)->name('event.management');
        Route::get('/event/{event}/seat-layout', App\Livewire\Admin\SeatLayoutManager::class)->name('events.seat-layout');
        Route::get('event/{event}/seat-layout/{layout}/preview', [SeatLayoutManager::class, 'previewLayout'])
            ->name('event.seat-layout.preview');
        Route::get('/event/index', App\Livewire\Admin\Event\Index::class)->name('event.index');
        Route::get('/event/create', App\Livewire\Admin\Event\Create::class)->name('event.create');
        Route::get('/event/edit/{id}', App\Livewire\Admin\Event\Edit::class)->name('event.edit');
    });

    // User/Customer Routes (All authenticated users)
    Route::prefix('app')->name('app.')->group(function () {
        
        // Event browsing and reservation
        Route::get('/events', ReservationIndex::class)->name('events');
        Route::get('/events/{event}/reserve', SeatReservation::class)->name('reservation');
        
        // User dashboard and profile
        Route::get('/dashboard', function() {
            return view('app.dashboard');
        })->name('dashboard');
        
        // Reservation management
        Route::get('/reservations', function() {
            // TODO: Implement reservation history page
            return view('app.reservations');
        })->name('reservations');
        
        // Payment page
        Route::get('/payment/{code}', function($code) {
            // TODO: Implement payment page
            return view('app.payment', compact('code'));
        })->name('payment');
        
        // User profile
        Route::get('/profile', function() {
            // TODO: Implement user profile page
            return view('app.profile');
        })->name('profile');
    });

});

// API Routes for AJAX calls (can be accessed by authenticated users)
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    
    // Real-time seat availability check
    Route::get('/events/{event}/availability', function($eventId) {
        try {
            // Get reserved seats for this event
            $reservedSeats = \DB::table('reservation_seats as rs')
                ->join('reservations as r', 'rs.reservation_id', '=', 'r.reservation_id')
                ->where('r.event_id', $eventId)
                ->whereIn('r.reservation_status', ['pending', 'confirmed'])
                ->where('r.expiry_date', '>', now())
                ->pluck('rs.seat_id')
                ->toArray();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'reserved_seats' => $reservedSeats,
                    'last_updated' => now()->toISOString()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get availability'
            ], 500);
        }
    })->name('event.availability');
    
    // Check specific seats before booking
    Route::post('/events/{event}/check-seats', function($eventId) {
        $request = request();
        $seatIds = $request->input('seat_ids', []);
        
        try {
            // Check if any of the requested seats are already reserved
            $conflictSeats = \DB::table('reservation_seats as rs')
                ->join('reservations as r', 'rs.reservation_id', '=', 'r.reservation_id')
                ->where('r.event_id', $eventId)
                ->whereIn('r.reservation_status', ['pending', 'confirmed'])
                ->where('r.expiry_date', '>', now())
                ->whereIn('rs.seat_id', $seatIds)
                ->pluck('rs.seat_id')
                ->toArray();
            
            $available = empty($conflictSeats);
            
            return response()->json([
                'success' => true,
                'available' => $available,
                'conflict_seats' => $conflictSeats,
                'message' => $available ? 'All seats are available' : 'Some seats are no longer available'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check seat availability'
            ], 500);
        }
    })->name('event.check-seats');
    
});

// Route untuk handle redirect setelah login
Route::get('/dashboard', function() {
    // Redirect user berdasarkan role setelah login
    if (auth()->user()->hasRole(['admin', 'superadmin'])) {
        return redirect()->route('event.management');
    } else {
        return redirect()->route('app.events');
    }
})->middleware('auth')->name('dashboard');