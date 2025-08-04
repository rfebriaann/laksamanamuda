<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Event Info -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <div class="flex items-center space-x-4">
            @if($event->event_image)
                <img src="{{ Storage::url($event->event_image) }}" alt="{{ $event->event_name }}" 
                     class="w-20 h-20 rounded-lg object-cover">
            @else
                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->event_name }}</h1>
                <p class="text-gray-600">{{ $event->venue_name }}</p>
                <p class="text-sm text-gray-500">
                    {{ $event->event_date->format('d M Y') }} • 
                    {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            {{ session('info') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Seat Layout -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pilih Kursi</h2>
                
                <!-- Stage -->
                <div class="text-center mb-8">
                    <div class="bg-gray-800 text-white py-2 px-8 rounded-lg inline-block">
                        PANGGUNG
                    </div>
                </div>

                <!-- Seat Layouts -->
                @foreach($seatLayouts as $layout)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ $layout['layout_name'] }}</h3>
                        
                        <!-- Seats Grid -->
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full">
                                @php
                                    $rows = collect($layout['seats'])->groupBy('seat_row');
                                @endphp
                                
                                @foreach($rows as $rowLetter => $seatsInRow)
                                    <div class="flex items-center justify-center mb-2">
                                        <div class="w-8 text-center font-medium text-gray-600 mr-2">{{ $rowLetter }}</div>
                                        <div class="flex">
                                            @foreach($seatsInRow as $seat)
                                                <button wire:click="toggleSeat({{ $seat['seat_id'] }})"
                                                        class="{{ $this->getSeatClass($seat) }}"
                                                        @if(!$seat['is_available']) disabled @endif
                                                        title="Kursi {{ $seat['seat_row'] }}{{ $seat['seat_number'] }} - {{ $seat['seat_type'] }} - Rp {{ number_format($seat['seat_price']) }}">
                                                    {{ $seat['seat_number'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <div class="w-8 text-center font-medium text-gray-600 ml-2">{{ $rowLetter }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Legend -->
                <div class="flex flex-wrap justify-center space-x-6 mt-6 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-200 rounded mr-2"></div>
                        <span>VIP Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-200 rounded mr-2"></div>
                        <span>Regular Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span>VIP Selected</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span>Regular Selected</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                        <span>Unavailable</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Booking</h3>
                
                <!-- Selected Seats -->
                <div class="mb-4">
                    <h4 class="font-medium text-gray-700 mb-2">Kursi Dipilih ({{ count($selectedSeats) }})</h4>
                    @if(!empty($selectedSeats))
                        <div class="space-y-1 max-h-32 overflow-y-auto">
                            @foreach($selectedSeats as $seatId)
                                @php
                                    $seat = collect($seatLayouts)->pluck('seats')->flatten(1)->firstWhere('seat_id', $seatId);
                                @endphp
                                @if($seat)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $seat['seat_row'] }}{{ $seat['seat_number'] }} ({{ $seat['seat_type'] }})</span>
                                        <span>Rp {{ number_format($seat['seat_price']) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Belum ada kursi dipilih</p>
                    @endif
                </div>

                <!-- Voucher Section -->
                @if(auth()->check() && auth()->user()->hasMembership())
                    <div class="mb-4 pb-4 border-b">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium text-gray-700">Voucher</h4>
                            @if(!$selectedVoucher)
                                <button wire:click="showVouchers" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    Pilih Voucher
                                </button>
                            @endif
                        </div>
                        
                        @if($selectedVoucher)
                            @php
                                $voucherClaim = collect($availableVouchers)->firstWhere('claim_id', $selectedVoucher);
                            @endphp
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-green-800">{{ $voucherClaim['voucher']['voucher_name'] }}</p>
                                        <p class="text-sm text-green-600">-Rp {{ number_format($discountAmount) }}</p>
                                    </div>
                                    <button wire:click="removeVoucher" 
                                            class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Price Summary -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($totalAmount) }}</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Diskon</span>
                            <span>-Rp {{ number_format($discountAmount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                        <span>Total</span>
                        <span>Rp {{ number_format($finalAmount) }}</span>
                    </div>
                </div>

                <!-- Action Button -->
                <button wire:click="proceedToPayment" 
                        @if(empty($selectedSeats)) disabled @endif
                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                    @if(empty($selectedSeats))
                        Pilih Kursi Terlebih Dahulu
                    @else
                        Lanjut ke Pembayaran
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Voucher Selection Modal -->
    <x-modal wire:model="showVoucherModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Voucher</h3>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($availableVouchers as $voucherClaim)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                         wire:click="selectVoucher({{ $voucherClaim['claim_id'] }})">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $voucherClaim['voucher']['voucher_name'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $voucherClaim['voucher']['voucher_description'] }}</p>
                                <p class="text-sm text-green-600 mt-1">
                                    Diskon: 
                                    @if($voucherClaim['voucher']['discount_type'] === 'percentage')
                                        {{ $voucherClaim['voucher']['discount_amount'] }}%
                                    @else
                                        Rp {{ number_format($voucherClaim['voucher']['discount_amount']) }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Berlaku hingga</p>
                                <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($voucherClaim['expires_at'])->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>Tidak ada voucher tersedia</p>
                    </div>
                @endforelse
            </div>

            <div class="flex justify-end mt-4">
                <button wire:click="$set('showVoucherModal', false)" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </x-modal>

    <x-modal wire:model="showConfirmationModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Reservasi</h3>
            <p class="text-gray-700 mb-4">Anda akan memesan {{ count($selectedSeats) }} kursi dengan total pembayaran Rp {{ number_format($finalAmount) }}.</p>
            <div class="flex justify-end mt-4">
                <button wire:click="confirmReservation" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Konfirmasi
                </button>
                <button wire:click="$set('showConfirmationModal', false)" 
                        class="ml-2 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
            </div>
        </div>
    </x-modal>
</div>