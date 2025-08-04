<?php

namespace App\Livewire\User;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use App\Models\Reservation;
use App\Models\VoucherClaim;
use Livewire\Component;

class SeatSelection extends Component
{
    public Event $event;
    public $seatLayouts = [];
    public $selectedSeats = [];
    public $totalAmount = 0;
    public $selectedVoucher = null;
    public $discountAmount = 0;
    public $finalAmount = 0;
    public $availableVouchers = [];
    
    public $showVoucherModal = false;
    public $showConfirmationModal = false;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadSeatLayouts();
        $this->loadAvailableVouchers();
    }

    public function render()
    {
        return view('livewire.user.seat-selection');
    }

    public function loadSeatLayouts()
    {
        $this->seatLayouts = $this->event->seatLayouts()
            ->with(['seats' => function ($query) {
                $query->select(['seat_id', 'layout_id', 'seat_number', 'seat_row', 'seat_type', 'seat_price', 'is_available'])
                      ->orderBy('seat_row')
                      ->orderBy('seat_number');
            }])
            ->get()
            ->toArray();
    }

    public function loadAvailableVouchers()
    {
        if (auth()->check() && auth()->user()->hasMembership()) {
            $this->availableVouchers = auth()->user()
                ->voucherClaims()
                ->active()
                ->with('voucher')
                ->get()
                ->toArray();
        }
    }

    public function toggleSeat($seatId)
    {
        $seat = Seat::findOrFail($seatId);
        
        if (!$seat->is_available) {
            session()->flash('error', 'Kursi tidak tersedia!');
            return;
        }

        if (in_array($seatId, $this->selectedSeats)) {
            // Remove seat
            $this->selectedSeats = array_filter($this->selectedSeats, fn($id) => $id != $seatId);
        } else {
            // Add seat (max 6 seats per reservation)
            if (count($this->selectedSeats) >= 6) {
                session()->flash('error', 'Maksimal 6 kursi per reservasi!');
                return;
            }
            $this->selectedSeats[] = $seatId;
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if (empty($this->selectedSeats)) {
            $this->totalAmount = 0;
            $this->finalAmount = 0;
            $this->discountAmount = 0;
            return;
        }

        $seats = Seat::whereIn('seat_id', $this->selectedSeats)->get();
        $this->totalAmount = $seats->sum('seat_price');

        $this->applyVoucherDiscount();
    }

    public function applyVoucherDiscount()
    {
        $this->discountAmount = 0;
        
        if ($this->selectedVoucher) {
            $voucherClaim = VoucherClaim::find($this->selectedVoucher);
            if ($voucherClaim && $voucherClaim->isValid()) {
                $this->discountAmount = $voucherClaim->voucher->calculateDiscount($this->totalAmount);
            }
        }

        $this->finalAmount = max(0, $this->totalAmount - $this->discountAmount);
    }

    public function selectVoucher($claimId)
    {
        $this->selectedVoucher = $claimId;
        $this->calculateTotal();
        $this->showVoucherModal = false;
    }

    public function removeVoucher()
    {
        $this->selectedVoucher = null;
        $this->calculateTotal();
    }

    public function showVouchers()
    {
        if (empty($this->availableVouchers)) {
            session()->flash('info', 'Anda tidak memiliki voucher yang tersedia.');
            return;
        }
        $this->showVoucherModal = true;
    }

    public function proceedToPayment()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Pilih minimal 1 kursi!');
            return;
        }

        $this->showConfirmationModal = true;
    }

    public function confirmReservation()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            \DB::beginTransaction();

            // Create reservation
            $reservation = Reservation::create([
                'reservation_code' => $this->generateReservationCode(),
                'user_id' => auth()->id(),
                'event_id' => $this->event->event_id,
                'reservation_status' => 'pending',
                'total_amount' => $this->finalAmount,
                'total_seats' => count($this->selectedSeats),
                'reservation_date' => now(),
                'expiry_date' => now()->addMinutes(15), // 15 minutes to complete payment
            ]);

            // Reserve seats
            $seats = Seat::whereIn('seat_id', $this->selectedSeats)->get();
            foreach ($seats as $seat) {
                $seat->reserve();
                
                $reservation->reservationSeats()->create([
                    'seat_id' => $seat->seat_id,
                    'seat_price' => $seat->seat_price,
                ]);
            }

            // Use voucher if selected
            if ($this->selectedVoucher && $this->discountAmount > 0) {
                $voucherClaim = VoucherClaim::find($this->selectedVoucher);
                $voucherClaim->use($reservation->reservation_id, $this->discountAmount);
            }

            \DB::commit();

            // Redirect to payment
            return redirect()->route('payment.create', $reservation->reservation_id);

        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.');
        }
    }

    private function generateReservationCode()
    {
        do {
            $code = 'LM' . now()->format('Ymd') . strtoupper(uniqid());
        } while (Reservation::where('reservation_code', $code)->exists());

        return $code;
    }

    public function getSeatClass($seat)
    {
        $baseClass = 'w-8 h-8 m-1 rounded text-xs font-bold cursor-pointer transition-all duration-200 flex items-center justify-center ';
        
        if (!$seat['is_available']) {
            return $baseClass . 'bg-gray-400 text-gray-600 cursor-not-allowed';
        }
        
        if (in_array($seat['seat_id'], $this->selectedSeats)) {
            return $baseClass . ($seat['seat_type'] === 'VIP' ? 
                'bg-yellow-500 text-white ring-2 ring-yellow-300' : 
                'bg-blue-500 text-white ring-2 ring-blue-300');
        }
        
        return $baseClass . ($seat['seat_type'] === 'VIP' ? 
            'bg-yellow-200 text-yellow-800 hover:bg-yellow-300' : 
            'bg-green-200 text-green-800 hover:bg-green-300');
    }
}