<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $event->event_name }}</h1>
                    <p class="mt-2 text-gray-600">
                        📅 {{ $event->event_date->format('d M Y') }} • 
                        🕐 {{ $event->event_time ?? '19:30' }} • 
                        📍 {{ $event->venue }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $totalSeats }} kursi dipilih
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        @if($reservationSuccess)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md mx-4">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Reservasi Berhasil!</h3>
                    <p class="text-gray-600 mb-4">Kode reservasi Anda:</p>
                    <p class="text-2xl font-bold text-blue-600 mb-6">{{ $reservationCode }}</p>
                    <div class="flex space-x-3">
                        <button wire:click="$set('reservationSuccess', false)" 
                                class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                            Tutup
                        </button>
                        <button wire:click="goToPayment" 
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Lanjut Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Layout Selector -->
                @if(count($layouts) > 1)
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Pilih Layout</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($layouts as $index => $layout)
                        <button wire:click="selectLayout({{ $index }})"
                                class="p-4 border-2 rounded-lg text-left transition-colors
                                {{ $selectedLayout['layout_id'] == $layout['layout_id'] ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <h4 class="font-semibold">{{ $layout['layout_name'] }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $layout['layout_config']['selling_mode'] == 'per_table' ? 'Per Meja' : 'Per Kursi' }}
                            </p>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Stage -->
                {{-- <div class="text-center mb-6">
                    <div class="inline-block bg-gray-800 text-white px-8 py-4 rounded-lg">
                        <h2 class="text-xl font-bold">🎭 PANGGUNG</h2>
                    </div>
                </div> --}}

                <!-- Seat Map -->
                @if($selectedLayout)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">{{ $selectedLayout['layout_name'] }}</h3>
                        @if(!empty($selectedSeats) || !empty($selectedTables))
                        <button wire:click="clearSelections" 
                                class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">
                            Reset Pilihan
                        </button>
                        @endif
                    </div>

                    <!-- Seat Canvas -->
                    <!-- Seat Canvas -->
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg h-[550px] w-[100%] overflow-auto"
                            style="
                                background-color: #f9fafb;
                                @if(!empty($selectedLayout['background_image']))
                                    @php
                                        // Set opacity untuk background image (0.1 = 10%, 0.5 = 50%, 1 = 100%)
                                        $backgroundOpacity = $selectedLayout['layout_config']['background_opacity'] ?? 0.5; // Default 30%
                                        $overlayOpacity = 1 - $backgroundOpacity; // Untuk overlay putih
                                    @endphp
                                    background-image: 
                                        linear-gradient(rgba(255, 255, 255, {{ $overlayOpacity }}), rgba(255, 255, 255, {{ $overlayOpacity }})),
                                        url('{{ asset('storage/' . $selectedLayout['background_image']) }}');
                                    background-size: contain;
                                    background-repeat: no-repeat;
                                    background-position: center;
                                @else
                                    background-image: linear-gradient(rgba(0,0,0,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,.05) 1px, transparent 1px);
                                    background-size: 20px 20px;
                                @endif
                            ">
                            
                            @if($sellingMode === 'per_seat')
                                <!-- Render Seats dari Database -->
                                @if(isset($selectedLayout['layout_config']['custom_seats']))
                                    @foreach($selectedLayout['layout_config']['custom_seats'] as $seat)
                                    <button wire:click="toggleSeatSelection('{{ $seat['id'] }}')"
                                            class="absolute flex items-center justify-center text-xs font-bold border-2 rounded cursor-pointer transition-all duration-200 hover:scale-110
                                            @if(in_array($seat['id'], $selectedSeats))
                                                bg-blue-500 border-blue-600 text-white transform scale-110
                                            @elseif(!($seat['is_available'] ?? true))
                                                bg-red-500 border-red-600 text-white cursor-not-allowed
                                            @elseif($seat['type'] === 'VIP')
                                                bg-yellow-400 border-yellow-500 text-gray-800 hover:bg-yellow-500
                                            @else
                                                bg-green-400 border-green-500 text-white hover:bg-green-500
                                            @endif"
                                            style="left: {{ $seat['x'] }}px; top: {{ $seat['y'] }}px; width: {{ $seat['width'] ?? 40 }}px; height: {{ $seat['height'] ?? 40 }}px;"
                                            title="Kursi {{ $seat['number'] }} - {{ $seat['type'] }} - Rp {{ number_format($seat['price'] ?? 0, 0, ',', '.') }}"
                                            @if(!($seat['is_available'] ?? true)) disabled @endif>
                                        {{ $seat['number'] }}
                                    </button>
                                    @endforeach
                                @endif
                            @else
                                <!-- Render Tables dari Database -->
                                @if(isset($selectedLayout['layout_config']['tables']))
                                    @foreach($selectedLayout['layout_config']['tables'] as $table)
                                        @php
                                            $tableId = (int) $table['id']; // Convert to integer
                                            $isSelected = in_array($tableId, $selectedTables);
                                            $isReserved = in_array($tableId, $reservedTables);
                                            $isAvailable = $table['is_available'] ?? true;
                                        @endphp
                                        <button wire:click="toggleTableSelection({{ $tableId }})"
                                                class="absolute flex items-center justify-center text-sm font-bold border-3 transition-all duration-200 hover:scale-105
                                                @if($isSelected)
                                                    border-blue-500 bg-blue-100 text-blue-800 transform scale-105
                                                @elseif($isReserved || !$isAvailable)
                                                    border-red-500 bg-red-100 text-red-800 cursor-not-allowed
                                                @else
                                                    border-purple-500 bg-purple-400 text-purple-800 hover:bg-purple-300 text-white
                                                @endif
                                                @if($table['shape'] === 'circle') rounded-full
                                                @elseif($table['shape'] === 'diamond') transform rotate-45
                                                @else rounded-lg @endif"
                                                style="left: {{ $table['x'] }}px; top: {{ $table['y'] }}px; width: {{ $table['width'] ?? 120 }}px; height: {{ $table['height'] ?? 120 }}px;"
                                                title="Meja {{ $table['number'] }} - {{ $table['capacity'] }} kursi - Rp {{ number_format($table['price'] ?? 0, 0, ',', '.') }} - ID: {{ $tableId }} - Available: {{ $isAvailable ? 'Yes' : 'No' }} - Reserved: {{ $isReserved ? 'Yes' : 'No' }}"
                                                data-table-id="{{ $tableId }}"
                                                @if($isReserved || !$isAvailable) disabled @endif>
                                            <div class="@if($table['shape'] === 'diamond') -rotate-45 @endif text-center">
                                                <div class="font-bold">{{ $table['number'] }}</div>
                                                {{-- <div class="text-xs">{{ $table['capacity'] }}p</div> --}}
                                                {{-- @if(config('app.debug'))
                                                    <div class="text-xs">ID:{{ $tableId }}</div>
                                                    <div class="text-xs">{{ $isReserved ? 'R' : ($isSelected ? 'S' : 'A') }}</div>
                                                @endif --}}
                                            </div>
                                        </button>
                                    @endforeach
                                @else
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-500 text-center">
                                        <p>Tidak ada meja yang tersedia</p>
                                        @if(config('app.debug'))
                                            <p class="text-xs mt-2">
                                                Layout Config: {{ json_encode($selectedLayout['layout_config'] ?? []) }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>

                    <!-- Legend -->
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold mb-3">Keterangan:</h4>
                        <div class="flex flex-wrap gap-4 text-sm">
                            @if($sellingMode === 'per_seat')
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-green-400 border border-green-500 rounded"></div>
                                <span>Regular - Rp {{ number_format($selectedLayout['layout_config']['regular_price'] ?? 150000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-yellow-400 border border-yellow-500 rounded"></div>
                                <span>VIP - Rp {{ number_format($selectedLayout['layout_config']['vip_price'] ?? 300000, 0, ',', '.') }}</span>
                            </div>
                            @else
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-purple-100 border-2 border-purple-500 rounded"></div>
                                <span>Meja Tersedia - Rp {{ number_format($selectedLayout['layout_config']['table_price'] ?? 500000, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-500 border border-blue-600 rounded"></div>
                                <span>Dipilih</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-red-500 border border-red-600 rounded"></div>
                                <span>Tidak Tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <!-- Selected Items -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Pilihan Anda</h3>
                        
                        @if($sellingMode === 'per_seat' && !empty($selectedSeats))
                            <div class="space-y-2 mb-4">
                                @foreach($selectedSeats as $seatId)
                                    @php
                                        $seat = collect($selectedLayout['layout_config']['custom_seats'])->firstWhere('id', $seatId);
                                    @endphp
                                    @if($seat)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="font-medium">Kursi {{ $seat['number'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $seat['type'] }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold">Rp {{ number_format($seat['price'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($sellingMode === 'per_table' && !empty($selectedTables))
                            <div class="space-y-2 mb-4">
                                @foreach($selectedTables as $tableId)
                                    @php
                                        $table = collect($selectedLayout['layout_config']['tables'])->firstWhere('id', $tableId);
                                    @endphp
                                    @if($table)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="font-medium">Meja {{ $table['number'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $table['capacity'] }} kursi</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold">Rp {{ number_format($table['price'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm mb-4">Belum ada {{ $sellingMode === 'per_table' ? 'meja' : 'kursi' }} dipilih</p>
                        @endif

                        <!-- Total -->
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center text-lg font-semibold">
                                <span>Total:</span>
                                <span class="text-green-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $totalSeats }} {{ $sellingMode === 'per_table' ? 'kursi' : 'kursi' }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if(!$showBookingSummary)
                        <button wire:click="proceedToBooking" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                                @if(empty($selectedSeats) && empty($selectedTables)) disabled @endif>
                            Lanjut ke Booking
                        </button>
                    @else
                        <!-- Customer Info Form -->
                        <div class="space-y-4 mb-6">
                            <h4 class="font-semibold">Informasi Pemesan</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input wire:model="customerName" 
                                       type="text" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Masukkan nama lengkap">
                                @error('customerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input wire:model="customerEmail" 
                                       type="email" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="email@example.com">
                                @error('customerEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                <input wire:model="customerPhone" 
                                       type="tel" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="08xxxxxxxxxx">
                                @error('customerPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Process Booking Button -->
                        <div class="space-y-3">
                            
                            <!-- Debug Info (hanya tampil jika APP_DEBUG=true) -->
                            @if(config('app.debug'))
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded text-xs">
                                <strong>Debug Info:</strong><br>
                                Mode: {{ $sellingMode }}<br>
                                Seats: {{ count($selectedSeats) }}<br>
                                Tables: {{ count($selectedTables) }}<br>
                                Total: Rp {{ number_format($totalAmount) }}<br>
                                User: {{ auth()->user()->name ?? 'Guest' }}
                            </div>
                            @endif
                            
                            <button wire:click="processReservation" 
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="processReservation">
                                <span wire:loading.remove wire:target="processReservation">
                                    Konfirmasi Reservasi
                                </span>
                                <span wire:loading wire:target="processReservation" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                            
                            <button wire:click="$set('showBookingSummary', false)" 
                                    class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                                Kembali
                            </button>
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-yellow-800">Penting!</p>
                                <p class="text-yellow-700 mt-1">
                                    Reservasi akan berakhir dalam 30 menit setelah dibuat. 
                                    Harap lakukan pembayaran sebelum waktu habis.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any JavaScript for seat interaction enhancements
    document.addEventListener('livewire:initialized', () => {
        // Listen for seat selection updates
        Livewire.on('seatSelectionUpdated', (selectedSeats) => {
            console.log('Selected seats updated:', selectedSeats);
            
            // Update seat visual states
            document.querySelectorAll('[data-seat-id]').forEach(seat => {
                const seatId = seat.dataset.seatId;
                if (selectedSeats.includes(seatId)) {
                    seat.classList.add('selected');
                } else {
                    seat.classList.remove('selected');
                }
            });
        });

        // Listen for table selection updates
        Livewire.on('tableSelectionUpdated', (selectedTables) => {
            console.log('Selected tables updated:', selectedTables);
            
            // Update table visual states
            document.querySelectorAll('[data-table-id]').forEach(table => {
                const tableId = table.dataset.tableId;
                if (selectedTables.includes(tableId)) {
                    table.classList.add('selected');
                } else {
                    table.classList.remove('selected');
                }
            });
        });
    });

    // Auto-refresh reserved items every 30 seconds
    setInterval(() => {
        if (window.Livewire) {
            window.Livewire.dispatch('refreshReservedItems');
        }
    }, 30000);

    function updateBackgroundStyle(property, value) {
    const canvas = document.querySelector('.relative.border-2.border-dashed');
    const currentStyle = canvas.style.backgroundImage;
    
    if (property === 'size') {
        canvas.style.backgroundSize = value;
    } else if (property === 'position') {
        canvas.style.backgroundPosition = value;
    } else if (property === 'opacity') {
        // Update opacity overlay
        const url = currentStyle.match(/url\([^)]+\)/)[0];
        const opacity = 1 - parseFloat(value);
        canvas.style.backgroundImage = `linear-gradient(rgba(255,255,255,${opacity}), rgba(255,255,255,${opacity})), ${url}`;
    }
}
</script>
@endpush