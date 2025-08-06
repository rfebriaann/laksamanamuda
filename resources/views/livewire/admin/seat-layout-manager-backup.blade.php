
@push('sstyles')
<style>
    .tool-btn.active {
        @apply border-2;
    }
    
    #seat-canvas {
        background-image: 
            linear-gradient(rgba(0,0,0,.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,.1) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    
    .seat-element {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
</style>
@endpush
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <a href="{{ route('event.management') }}" class="text-indigo-600 hover:text-indigo-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-sm text-gray-600">Interactive Seat Layout Manager</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Layout Kursi</h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Event: <span class="font-semibold">{{ $event->event_name }}</span> - 
                        {{ $event->event_date->format('d M Y') }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button wire:click="createLayout" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Buat Layout Baru
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Seat Layouts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($seatLayouts as $layout)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Layout Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $layout['layout_name'] }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $layout['seats_count'] }} kursi total
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="editLayout({{ $layout['layout_id'] }})" 
                                        class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
                                        title="Edit Layout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteLayout({{ $layout['layout_id'] }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus layout ini?"
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                        title="Hapus Layout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Preview -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Layout Preview</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                @php
                                    $config = $layout['layout_config'];
                                    $customSeats = $config['custom_seats'] ?? [];
                                    $vipCount = collect($customSeats)->where('type', 'VIP')->count();
                                    $regularCount = collect($customSeats)->where('type', 'Regular')->count();
                                @endphp
                                
                                <!-- Stage -->
                                <div class="mb-3">
                                    <div class="bg-gray-800 text-white text-center py-2 px-4 rounded text-xs font-medium">
                                        PANGGUNG
                                    </div>
                                </div>

                                <!-- Custom Seats Preview -->
                                <div class="relative bg-white border-2 border-dashed border-gray-200 rounded-lg h-32 overflow-hidden">
                                    @foreach($customSeats as $seat)
                                        <div class="absolute w-3 h-3 rounded-sm {{ $seat['type'] === 'VIP' ? 'bg-yellow-400' : 'bg-blue-400' }}"
                                             style="left: {{ $seat['x'] }}px; top: {{ $seat['y'] }}px;"
                                             title="{{ $seat['type'] }} - {{ $seat['label'] ?? $seat['id'] }}">
                                        </div>
                                    @endforeach
                                    
                                    @if(empty($customSeats))
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                            Layout Kosong
                                        </div>
                                    @endif
                                </div>

                                <!-- Legend -->
                                <div class="flex justify-center space-x-4 mt-3 text-xs">
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-blue-400 rounded-sm"></div>
                                        <span class="text-gray-600">Regular ({{ $regularCount }})</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-yellow-400 rounded-sm"></div>
                                        <span class="text-gray-600">VIP ({{ $vipCount }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Layout Info -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Kursi:</span>
                                <span class="font-medium">{{ count($customSeats) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga Regular:</span>
                                <span class="font-medium">Rp {{ number_format($config['regular_price'] ?? 150000) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga VIP:</span>
                                <span class="font-medium">Rp {{ number_format($config['vip_price'] ?? 300000) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada layout kursi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat layout kursi pertama untuk event ini.</p>
                        <div class="mt-6">
                            <button wire:click="createLayout" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Buat Layout Pertama
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create/Edit Layout Modal -->
    @if($showLayoutModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="layout-modal">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl transform transition-all w-full max-w-7xl max-h-[90vh] overflow-hidden">
                    <form wire:submit="saveLayout" class="h-full flex flex-col">
                        <!-- Header -->
                        <div class="bg-white px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $editingLayoutId ? 'Edit Layout Kursi' : 'Buat Layout Kursi Baru' }}
                                </h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 flex overflow-hidden">
                            <!-- Settings Sidebar -->
                            <div class="w-80 bg-gray-50 border-r border-gray-200 p-6 overflow-y-auto">
                                <div class="space-y-6">
                                    <!-- Layout Name -->
                                    <div>
                                        <label for="layout_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Layout
                                        </label>
                                        <input wire:model="layout_name" 
                                               type="text" 
                                               id="layout_name"
                                               placeholder="contoh: Main Hall Layout"
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('layout_name') border-red-300 @enderror">
                                        @error('layout_name') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>

                                    <!-- Tools -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Tools</h4>
                                        <div class="space-y-2">
                                            <button type="button" 
                                                    onclick="SeatManager.setTool('regular')"
                                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-blue-50 hover:border-blue-300 tool-btn"
                                                    data-tool="regular">
                                                <div class="w-4 h-4 bg-blue-400 rounded mr-3"></div>
                                                Tambah Kursi Regular
                                            </button>
                                            <button type="button" 
                                                    onclick="SeatManager.setTool('vip')"
                                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-yellow-50 hover:border-yellow-300 tool-btn"
                                                    data-tool="vip">
                                                <div class="w-4 h-4 bg-yellow-400 rounded mr-3"></div>
                                                Tambah Kursi VIP
                                            </button>
                                            <button type="button" 
                                                    onclick="SeatManager.setTool('select')"
                                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 hover:border-gray-400 tool-btn active"
                                                    data-tool="select">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.121 2.122"/>
                                                </svg>
                                                Pilih/Geser Kursi
                                            </button>
                                            <button type="button" 
                                                    onclick="SeatManager.setTool('delete')"
                                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-red-50 hover:border-red-300 tool-btn"
                                                    data-tool="delete">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus Kursi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h4>
                                        <div class="space-y-2">
                                            <button type="button" 
                                                    onclick="SeatManager.createGridLayout(10, 20)"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                                Grid 10x20 (200 kursi)
                                            </button>
                                            <button type="button" 
                                                    onclick="SeatManager.createGridLayout(15, 25)"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                                Grid 15x25 (375 kursi)
                                            </button>
                                            <button type="button" 
                                                    onclick="SeatManager.clearAllSeats()"
                                                    class="w-full px-3 py-2 border border-red-300 rounded-md text-sm text-red-600 hover:bg-red-50">
                                                Hapus Semua Kursi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Pricing -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Harga Tiket</h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label for="regular_price" class="block text-xs font-medium text-gray-700 mb-1">
                                                    Harga Regular
                                                </label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                                    <input wire:model="regular_price" 
                                                           type="number" 
                                                           id="regular_price"
                                                           step="1000"
                                                           min="0"
                                                           class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label for="vip_price" class="block text-xs font-medium text-gray-700 mb-1">
                                                    Harga VIP
                                                </label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                                    <input wire:model="vip_price" 
                                                           type="number" 
                                                           id="vip_price"
                                                           step="1000"
                                                           min="0"
                                                           class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Statistics -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total Kursi:</span>
                                                <span class="font-medium" id="total-seats">0</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Kursi Regular:</span>
                                                <span class="font-medium" id="regular-seats">0</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Kursi VIP:</span>
                                                <span class="font-medium" id="vip-seats">0</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Est. Revenue:</span>
                                                <span class="font-medium" id="estimated-revenue">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Layout Canvas -->
                            <div class="flex-1 p-6 overflow-auto">
                                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    wire:loading.attr="disabled"
                                    onclick="SeatManager.syncWithLivewire()">
                                <span wire:loading.remove>
                                    {{ $editingLayoutId ? 'Update Layout' : 'Simpan Layout' }}
                                </span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Layout Designer</h4>
                                    <p class="text-xs text-gray-500 mb-4">
                                        Klik untuk menambah kursi, drag untuk memindahkan, atau klik kursi untuk mengubah tipe
                                    </p>
                                </div>

                                <!-- Stage -->
                                <div class="mb-6">
                                    <div class="bg-gray-800 text-white text-center py-4 px-6 rounded-lg text-sm font-medium max-w-md mx-auto">
                                        PANGGUNG
                                    </div>
                                </div>

                                <!-- Canvas Area -->
                                <div class="relative bg-white border-2 border-gray-300 rounded-lg min-h-96 overflow-hidden"
                                     id="seat-canvas"
                                     style="height: 500px; width: 100%;">
                                    
                                    <!-- Grid Background -->
                                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                                        <svg width="100%" height="100%">
                                            <defs>
                                                <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#333" stroke-width="1"/>
                                                </pattern>
                                            </defs>
                                            <rect width="100%" height="100%" fill="url(#grid)" />
                                        </svg>
                                    </div>

                                    <!-- Seats will be dynamically added here -->
                                    <div id="seats-container" class="relative h-full w-full"></div>
                                </div>

                                <!-- Instructions -->
                                <div class="mt-4 text-xs text-gray-500">
                                    <p><strong>Instruksi:</strong></p>
                                    <ul class="list-disc list-inside space-y-1 mt-1">
                                        <li>Pilih tool di sidebar kiri</li>
                                        <li>Klik di canvas untuk menambah kursi baru</li>
                                        <li>Drag kursi untuk memindahkan posisi</li>
                                        <li>Klik kursi untuk mengubah tipe (Regular/VIP)</li>
                                        <li>Gunakan tool delete untuk menghapus kursi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- JavaScript - Fixed Structure -->
@push('scripts')
<script>
// Global Seat Manager Object
window.SeatManager = {
    currentTool: 'select',
    seats: @json($custom_seats ?? []),
    isDragging: false,
    dragTarget: null,
    seatCounter: 0,

    init() {
        this.seatCounter = this.seats.length;
        this.bindEvents();
        this.renderSeats();
        this.updateStatistics();
    },

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('seat-canvas');
            if (canvas) {
                canvas.addEventListener('click', (e) => this.handleCanvasClick(e));
            }

            const regularPriceInput = document.getElementById('regular_price');
            const vipPriceInput = document.getElementById('vip_price');
            
            if (regularPriceInput) regularPriceInput.addEventListener('input', () => this.updateStatistics());
            if (vipPriceInput) vipPriceInput.addEventListener('input', () => this.updateStatistics());
        });
    },

    setTool(tool) {
        this.currentTool = tool.toLowerCase();

        // Update button states
        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-blue-100', 'border-blue-500', 'bg-yellow-100', 'border-yellow-500', 'bg-red-100', 'border-red-500', 'bg-gray-100', 'border-gray-500');
        });

        const activeBtn = document.querySelector(`[data-tool="${tool.toLowerCase()}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            
            // Add specific colors based on tool
            switch(tool.toLowerCase()) {
                case 'regular':
                    activeBtn.classList.add('bg-blue-100', 'border-blue-500');
                    break;
                case 'vip':
                    activeBtn.classList.add('bg-yellow-100', 'border-yellow-500');
                    break;
                case 'delete':
                    activeBtn.classList.add('bg-red-100', 'border-red-500');
                    break;
                default:
                    activeBtn.classList.add('bg-gray-100', 'border-gray-500');
            }
        }

        // Update cursor
        const canvas = document.getElementById('seat-canvas');
        if (canvas) {
            canvas.style.cursor = tool === 'select' ? 'default' : 
                                 tool === 'delete' ? 'crosshair' : 'copy';
        }
    },

    createGridLayout(rows, cols) {
        if (!confirm(`Buat layout grid ${rows}x${cols}? Ini akan menghapus kursi yang ada.`)) return;

        this.seats = [];
        const seatSize = 24;
        const spacing = 4;
        const startX = 50;
        const startY = 50;

        let seatId = 1;

        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const x = startX + (col * (seatSize + spacing));
                const y = startY + (row * (seatSize + spacing));

                this.seats.push({
                    id: seatId++,
                    x: x,
                    y: y,
                    type: 'Regular',
                    row: String.fromCharCode(65 + row),
                    number: col + 1
                });
            }
        }

        this.seatCounter = seatId - 1;
        this.renderSeats();
        this.updateStatistics();
    },

    clearAllSeats() {
        if (!confirm('Hapus semua kursi? Tindakan ini tidak dapat dibatalkan.')) return;

        this.seats = [];
        this.seatCounter = 0;
        this.renderSeats();
        this.updateStatistics();
    },

    renderSeats() {
        const container = document.getElementById('seats-container');
        if (!container) return;
        
        container.innerHTML = '';

        this.seats.forEach(seat => {
            const seatElement = document.createElement('div');
            seatElement.className = `absolute w-6 h-6 rounded cursor-pointer transition-all duration-200 border-2 border-white shadow-sm hover:scale-110 ${
                seat.type === 'VIP' ? 'bg-yellow-400 hover:bg-yellow-500' : 'bg-blue-400 hover:bg-blue-500'
            }`;
            seatElement.style.left = seat.x + 'px';
            seatElement.style.top = seat.y + 'px';
            seatElement.dataset.seatId = seat.id;
            seatElement.title = `${seat.type} - ${seat.row}${seat.number}`;

            // Add seat number
            seatElement.innerHTML = `<span class="text-xs text-white font-bold flex items-center justify-center h-full">${seat.number}</span>`;

            // Event listeners
            seatElement.addEventListener('mousedown', (e) => this.handleSeatMouseDown(e));
            seatElement.addEventListener('click', (e) => this.handleSeatClick(e));

            container.appendChild(seatElement);
        });
    },

    handleCanvasClick(e) {
        if (this.currentTool === 'regular' || this.currentTool === 'vip') {
            const canvas = document.getElementById('seat-canvas');
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left - 12; // Center the seat
            const y = e.clientY - rect.top - 12;

            // Don't add if clicking on existing seat
            if (e.target.dataset.seatId) return;

            this.seatCounter++;
            const newSeat = {
                id: this.seatCounter,
                x: Math.max(0, Math.min(x, canvas.clientWidth - 24)),
                y: Math.max(0, Math.min(y, canvas.clientHeight - 24)),
                type: this.currentTool === 'vip' ? 'VIP' : 'Regular',
                row: 'A',
                number: this.seatCounter
            };

            this.seats.push(newSeat);
            this.renderSeats();
            this.updateStatistics();
        }
    },

    handleSeatMouseDown(e) {
        if (this.currentTool === 'select') {
            this.isDragging = true;
            this.dragTarget = e.target;
            this.dragTarget.style.zIndex = '1000';

            const moveHandler = (e) => {
                if (!this.isDragging || !this.dragTarget) return;

                const canvas = document.getElementById('seat-canvas');
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left - 12;
                const y = e.clientY - rect.top - 12;

                const maxX = canvas.clientWidth - 24;
                const maxY = canvas.clientHeight - 24;

                const newX = Math.max(0, Math.min(x, maxX));
                const newY = Math.max(0, Math.min(y, maxY));

                this.dragTarget.style.left = newX + 'px';
                this.dragTarget.style.top = newY + 'px';

                // Update seat data
                const seatId = parseInt(this.dragTarget.dataset.seatId);
                const seat = this.seats.find(s => s.id === seatId);
                if (seat) {
                    seat.x = newX;
                    seat.y = newY;
                }
            };

            const upHandler = () => {
                this.isDragging = false;
                if (this.dragTarget) {
                    this.dragTarget.style.zIndex = '';
                    this.dragTarget = null;
                }
                document.removeEventListener('mousemove', moveHandler);
                document.removeEventListener('mouseup', upHandler);
            };

            document.addEventListener('mousemove', moveHandler);
            document.addEventListener('mouseup', upHandler);

            e.preventDefault();
            e.stopPropagation();
        }
    },

    handleSeatClick(e) {
        e.stopPropagation();

        const seatId = parseInt(e.target.dataset.seatId);
        const seat = this.seats.find(s => s.id === seatId);

        if (!seat) return;

        if (this.currentTool === 'delete') {
            // Delete seat
            this.seats = this.seats.filter(s => s.id !== seatId);
            this.renderSeats();
            this.updateStatistics();
        } else if (this.currentTool === 'select') {
            // Toggle seat type
            seat.type = seat.type === 'VIP' ? 'Regular' : 'VIP';
            this.renderSeats();
            this.updateStatistics();
        }
    },

    updateStatistics() {
        const regularCount = this.seats.filter(s => s.type === 'Regular').length;
        const vipCount = this.seats.filter(s => s.type === 'VIP').length;
        const totalSeats = this.seats.length;

        const regularPriceInput = document.getElementById('regular_price');
        const vipPriceInput = document.getElementById('vip_price');
        
        const regularPrice = regularPriceInput ? parseInt(regularPriceInput.value) || 150000 : 150000;
        const vipPrice = vipPriceInput ? parseInt(vipPriceInput.value) || 300000 : 300000;

        const estimatedRevenue = (regularCount * regularPrice) + (vipCount * vipPrice);

        const totalSeatsEl = document.getElementById('total-seats');
        const regularSeatsEl = document.getElementById('regular-seats');
        const vipSeatsEl = document.getElementById('vip-seats');
        const estimatedRevenueEl = document.getElementById('estimated-revenue');

        if (totalSeatsEl) totalSeatsEl.textContent = totalSeats;
        if (regularSeatsEl) regularSeatsEl.textContent = regularCount;
        if (vipSeatsEl) vipSeatsEl.textContent = vipCount;
        if (estimatedRevenueEl) estimatedRevenueEl.textContent = 'Rp ' + estimatedRevenue.toLocaleString('id-ID');
    },

    syncWithLivewire() {
        // Sync seats data with Livewire before form submission
        if (window.Livewire && @this) {
            @this.set('custom_seats', this.seats);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    SeatManager.init();
});

// Livewire hooks
document.addEventListener('livewire:initialized', () => {
    SeatManager.init();
});
</script>
@endpush