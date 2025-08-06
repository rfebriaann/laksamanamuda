
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

<script>
// Enhanced Seat Layout Manager with Resizable Elements
window.SeatLayoutManager = {
    // Core properties
    currentTool: 'select',
    sellingMode: @json($selling_mode ?? 'per_seat'),
    seats: @json($custom_seats ?? []),
    tables: @json($tables ?? []),
    
    // Counters
    seatCounter: 0,
    tableCounter: 0,
    
    // Selection and interaction
    selectedElements: [],
    
    // Grid settings
    gridSize: 20,
    snapToGrid: true,
    
    // Background image
    backgroundOpacity: 0.3,
    
    // Event handlers
    canvasClickHandler: null,
    
    // Zoom settings
    zoomLevel: 1,
    previewZoomLevel: 1,
    
    // Table shape
    currentTableShape: 'square',
    
    // Resize settings
    minSeatSize: 32,
    maxSeatSize: 80,
    minTableSize: 60,
    maxTableSize: 200,
    
    init() {
        console.log('🚀 Initializing Enhanced Seat Layout Manager');
        console.log('Initial data - Seats:', this.seats.length, 'Tables:', this.tables.length);
        
        this.initializeCounters();
        this.setupCanvas();
        this.setupModeToggle();
        this.updateInterface();
        this.renderAll();
        this.setupKeyboardShortcuts();
        this.updateStatistics();
        
        // Set default tool
        this.setTool('select');
        
        console.log('✅ Enhanced initialization complete');
    },

    initializeCounters() {
        this.seatCounter = this.seats.length > 0 ? Math.max(...this.seats.map(s => parseInt(s.id.replace('seat_', '') || 0))) : 0;
        this.tableCounter = this.tables.length > 0 ? Math.max(...this.tables.map(t => parseInt(t.id.replace('table_', '') || 0))) : 0;
    },

    setupCanvas() {
        const canvas = document.getElementById('seat-canvas');
        if (!canvas) {
            console.error('❌ Canvas not found!');
            return;
        }

        console.log('🎨 Setting up canvas interactions with resize support');

        // Remove existing event listeners to prevent duplicates
        canvas.removeEventListener('click', this.canvasClickHandler);
        
        // Create bound handler
        this.canvasClickHandler = (event) => {
            console.log('🖱️ Canvas clicked', event.target.tagName, event.target.className);
            
            // Skip resize handles
            if (event.target.classList.contains('resize-handle')) {
                return;
            }
            
            // Only handle clicks on the canvas itself or elements-container
            if (event.target !== canvas && !event.target.closest('#elements-container')) {
                return;
            }
            
            // Don't handle clicks on existing elements
            if (event.target.classList.contains('seat-element') || 
                event.target.classList.contains('table-element') ||
                event.target.closest('.seat-element') ||
                event.target.closest('.table-element')) {
                return;
            }
            
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            
            console.log('📍 Click position:', x, y, 'Current tool:', this.currentTool);
            
            const snappedX = this.snapToGrid ? Math.round(x / this.gridSize) * this.gridSize : x;
            const snappedY = this.snapToGrid ? Math.round(y / this.gridSize) * this.gridSize : y;
            
            this.handleCanvasClick(snappedX, snappedY);
        };
        
        // Add the event listener
        canvas.addEventListener('click', this.canvasClickHandler);

        // Update canvas cursor
        this.updateCanvasCursor();
        
        console.log('✅ Canvas setup complete with resize support');
    },

    setupModeToggle() {
        const modeToggle = document.getElementById('mode-toggle');
        if (!modeToggle) return;

        modeToggle.addEventListener('click', (e) => {
            if (e.target.dataset.mode) {
                this.setSellingMode(e.target.dataset.mode);
            }
        });
    },

    setSellingMode(mode) {
        console.log('🔄 Changing selling mode from', this.sellingMode, 'to', mode);
        
        this.sellingMode = mode;
        
        // Update Livewire
        if (window.Livewire) {
            @this.set('selling_mode', mode);
        }
        
        // Update UI
        document.querySelectorAll('#mode-toggle button').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });
        
        // Show/hide table shape section
        const tableShapeSection = document.getElementById('table-shape-section');
        if (tableShapeSection) {
            tableShapeSection.style.display = mode === 'per_table' ? 'block' : 'none';
        }
        
        // Clear all data when switching modes to avoid confusion
        this.clearAll();
        
        this.updateInterface();
        
        console.log('✅ Selling mode changed to:', this.sellingMode);
    },

    setTableShape(shape) {
        console.log('🔧 Setting table shape to:', shape);
        this.currentTableShape = shape;
        
        // Update button states
        document.querySelectorAll('.table-shape-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.shape === shape);
        });
        
        console.log('✅ Table shape set to:', this.currentTableShape);
    },

    updateInterface() {
        console.log('🖥️ Updating interface for mode:', this.sellingMode);
        this.updateModeDescription();
        this.updateTools();
        this.updateQuickActions();
        this.updatePricingSection();
        this.updateCanvasInstruction();
        console.log('✅ Interface updated');
    },

    updateModeDescription() {
        const description = document.getElementById('mode-description');
        if (description) {
            description.textContent = this.sellingMode === 'per_table' 
                ? 'Pelanggan membeli seluruh meja dengan semua kursinya'
                : 'Pelanggan membeli kursi individual';
        }
    },

    updateTools() {
        const container = document.getElementById('tools-container');
        if (!container) return;

        const tools = [];
        
        if (this.sellingMode === 'per_table') {
            // Only table tool for per_table mode
            tools.push({
                id: 'table',
                name: 'Tambah Meja',
                color: 'purple',
                icon: '<div class="w-4 h-4 bg-purple-400 rounded mr-3"></div>'
            });
        } else {
            // Seat tools for per_seat mode
            tools.push(
                {
                    id: 'regular',
                    name: 'Tambah Kursi Regular',
                    color: 'blue',
                    icon: '<div class="w-4 h-4 bg-blue-400 rounded mr-3"></div>'
                },
                {
                    id: 'vip',
                    name: 'Tambah Kursi VIP',
                    color: 'yellow',
                    icon: '<div class="w-4 h-4 bg-yellow-400 rounded mr-3"></div>'
                }
            );
        }
        
        // Common tools for both modes
        tools.push(
            {
                id: 'select',
                name: 'Pilih/Geser',
                color: 'gray',
                icon: '<svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"></path></svg>'
            },
            {
                id: 'delete',
                name: 'Hapus',
                color: 'red',
                icon: '<svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
            }
        );

        container.innerHTML = tools.map(tool => `
            <button type="button" 
                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-${tool.color}-50 hover:border-${tool.color}-300 tool-btn ${this.currentTool === tool.id ? 'active' : ''}"
                    data-tool="${tool.id}"
                    onclick="SeatLayoutManager.setTool('${tool.id}')">
                ${tool.icon}
                ${tool.name}
            </button>
        `).join('');
    },

    updateQuickActions() {
        const container = document.getElementById('quick-actions');
        if (!container) return;

        const actions = [];
        
        if (this.sellingMode === 'per_table') {
            actions.push(
                { name: 'Layout Restaurant (20 meja)', action: 'createRestaurantLayout()' },
                { name: 'Layout Banquet (30 meja)', action: 'createBanquetLayout()' }
            );
        } else {
            actions.push(
                { name: 'Grid 10x20 (200 kursi)', action: 'createGridLayout(10, 20)' },
                { name: 'Layout Theater', action: 'createTheaterLayout()' }
            );
        }
        
        actions.push({ name: 'Hapus Semua', action: 'clearAll()', class: 'text-red-600 border-red-300 hover:bg-red-50' });

        container.innerHTML = actions.map(action => `
            <button type="button" 
                    onclick="SeatLayoutManager.${action.action}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 ${action.class || ''}">
                ${action.name}
            </button>
        `).join('');
    },

    updatePricingSection() {
        const container = document.getElementById('pricing-section');
        if (!container) return;

        let content = '';
        
        if (this.sellingMode === 'per_table') {
            content = `
                <div>
                    <label for="table_price" class="block text-xs font-medium text-gray-700 mb-1">Harga per Meja</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="table_price" 
                               type="number" 
                               id="table_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="table_capacity" class="block text-xs font-medium text-gray-700 mb-1">Kapasitas Default Meja</label>
                    <input wire:model.live="table_capacity" 
                           type="number" 
                           id="table_capacity"
                           min="2"
                           max="12"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            `;
        } else {
            content = `
                <div>
                    <label for="regular_price" class="block text-xs font-medium text-gray-700 mb-1">Harga Regular</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="regular_price" 
                               type="number" 
                               id="regular_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="vip_price" class="block text-xs font-medium text-gray-700 mb-1">Harga VIP</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="vip_price" 
                               type="number" 
                               id="vip_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
            `;
        }
        
        container.innerHTML = content;
    },

    updateCanvasInstruction() {
        const instruction = document.getElementById('canvas-instruction');
        if (instruction) {
            instruction.textContent = this.sellingMode === 'per_table'
                ? 'Pilih bentuk meja, klik untuk menambah meja, drag untuk memindahkan, resize dengan handle di pojok. Customer memesan langsung per meja.'
                : 'Klik untuk menambah kursi, drag untuk memindahkan, resize dengan handle di pojok. Klik kursi untuk mengubah tipe.';
        }
    },

    setTool(tool) {
        console.log('🔧 Setting tool to:', tool);
        this.currentTool = tool;
        this.clearSelection();
        
        // Update button states
        document.querySelectorAll('.tool-btn').forEach(btn => {
            const isActive = btn.dataset.tool === tool;
            btn.classList.toggle('active', isActive);
        });
        
        this.updateCanvasCursor();
        console.log('🎯 Current tool is now:', this.currentTool);
    },

    updateCanvasCursor() {
        const canvas = document.getElementById('seat-canvas');
        if (canvas) {
            canvas.className = canvas.className.replace(/tool-\w+/g, '');
            canvas.classList.add(`tool-${this.currentTool}`);
        }
    },

    handleCanvasClick(x, y) {
        console.log('🎯 Handling canvas click at', x, y, 'with tool:', this.currentTool);
        
        switch (this.currentTool) {
            case 'regular':
                console.log('Adding regular seat');
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('Regular', x, y);
                } else {
                    // In per_table mode, regular tool is not available
                    console.log('Regular seat tool not available in per_table mode');
                }
                break;
            case 'vip':
                console.log('Adding VIP seat');
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('VIP', x, y);
                } else {
                    // In per_table mode, VIP tool is not available
                    console.log('VIP seat tool not available in per_table mode');
                }
                break;
            case 'table':
                console.log('Adding table with shape:', this.currentTableShape);
                if (this.sellingMode === 'per_table') {
                    this.createTable(x, y, this.currentTableShape);
                } else {
                    console.log('Table tool not available in per_seat mode');
                }
                break;
            default:
                console.log('No action for tool:', this.currentTool);
        }
    },

    createSeat(type, x, y, tableId = null, width = 44, height = 44) {
        this.seatCounter++;
        console.log('🪑 Creating seat:', type, 'at', x, y, 'counter:', this.seatCounter);
        
        const seat = {
            id: 'seat_' + this.seatCounter,
            x: x,
            y: y,
            type: type,
            row: this.generateSeatRow(y),
            number: this.seatCounter,
            table_id: tableId,
            width: width,
            height: height
        };
        
        this.seats.push(seat);
        console.log('📊 Total seats now:', this.seats.length);
        
        this.renderSeat(seat);
        this.updateStatistics();
        
        console.log('✅ Seat created successfully');
    },

    createTable(x, y, shape = 'square') {
        this.tableCounter++;
        console.log('🍽️ Creating table at', x, y, 'counter:', this.tableCounter, 'shape:', shape);
        
        const capacity = parseInt(document.getElementById('table_capacity')?.value || '4');
        
        // Set default dimensions based on shape
        let width = 120, height = 120;
        switch (shape) {
            case 'rectangle':
                width = 160;
                height = 100;
                break;
            case 'circle':
                width = 120;
                height = 120;
                break;
            case 'diamond':
                width = 120;
                height = 120;
                break;
            default: // square
                width = 120;
                height = 120;
        }
        
        console.log('Table dimensions:', width, 'x', height, 'capacity:', capacity);
        
        const table = {
            id: 'table_' + this.tableCounter,
            x: x,
            y: y,
            number: 'T' + this.tableCounter,
            capacity: capacity,
            shape: shape,
            width: width,
            height: height
        };
        
        // No seats for per_table mode - customers book the whole table
        console.log('Table created without individual seats (per_table mode)');
        
        this.tables.push(table);
        this.renderTable(table);
        
        console.log('📊 Total tables now:', this.tables.length);
        
        this.updateStatistics();
        
        console.log('✅ Table created successfully');
    },

    // Removed generateTableSeats - no individual seats for tables in per_table mode

    // Removed addSeatToNearestTable - not needed in per_table mode

    renderAll() {
        console.log('🎨 Rendering all elements - Seats:', this.seats.length, 'Tables:', this.tables.length);
        
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Elements container not found for renderAll!');
            return;
        }
        
        // Clear existing elements
        container.innerHTML = '';
        console.log('🧹 Cleared existing elements');
        
        // Render tables first (lower z-index)
        this.tables.forEach((table, index) => {
            console.log(`🍽️ Rendering table ${index + 1}/${this.tables.length}:`, table.id);
            this.renderTable(table);
        });
        
        // Render seats
        this.seats.forEach((seat, index) => {
            console.log(`🪑 Rendering seat ${index + 1}/${this.seats.length}:`, seat.id);
            this.renderSeat(seat);
        });
        
        console.log('✅ All elements rendered successfully');
    },

    renderTable(table) {
        const container = document.getElementById('elements-container');
        const element = document.createElement('div');
        
        element.className = `table-element shape-${table.shape || 'square'}`;
        element.style.left = table.x + 'px';
        element.style.top = table.y + 'px';
        element.style.width = (table.width || 120) + 'px';
        element.style.height = (table.height || 120) + 'px';
        element.dataset.tableId = table.id;
        
        // Apply rotation for diamond shape
        if (table.shape === 'diamond') {
            element.style.transform = 'rotate(45deg)';
        }
        
        element.innerHTML = `
            <div class="table-content">
                <div class="table-number">${table.number}</div>
                <div class="table-capacity">${table.capacity} kursi</div>
            </div>
            ${this.createResizeHandles()}
        `;
        
        this.makeTableInteractive(element);
        container.appendChild(element);
    },

    renderSeat(seat) {
        const container = document.getElementById('elements-container');
        const element = document.createElement('div');
        const isTableSeat = seat.table_id !== null;
        
        element.className = `seat-element ${seat.type.toLowerCase()} ${isTableSeat ? 'table-seat' : ''}`;
        element.style.left = seat.x + 'px';
        element.style.top = seat.y + 'px';
        element.style.width = (seat.width || 44) + 'px';
        element.style.height = (seat.height || 44) + 'px';
        element.dataset.seatId = seat.id;
        
        element.innerHTML = `
            <span>${seat.number}</span>
            ${this.createResizeHandles()}
        `;
        
        this.makeSeatInteractive(element);
        container.appendChild(element);
    },

    createResizeHandles() {
        return `
            <div class="resize-handles">
                <div class="resize-handle nw" data-direction="nw"></div>
                <div class="resize-handle ne" data-direction="ne"></div>
                <div class="resize-handle sw" data-direction="sw"></div>
                <div class="resize-handle se" data-direction="se"></div>
                <div class="resize-handle n" data-direction="n"></div>
                <div class="resize-handle s" data-direction="s"></div>
                <div class="resize-handle w" data-direction="w"></div>
                <div class="resize-handle e" data-direction="e"></div>
            </div>
        `;
    },

    makeTableInteractive(element) {
        const tableId = element.dataset.tableId;
        const table = this.tables.find(t => t.id === tableId);
        
        // Make draggable
        interact(element)
            .draggable({
                listeners: {
                    start: () => {
                        element.classList.add('dragging');
                    },
                    move: (event) => {
                        const x = (parseFloat(element.getAttribute('data-x')) || 0) + event.dx;
                        const y = (parseFloat(element.getAttribute('data-y')) || 0) + event.dy;
                        
                        const snappedX = this.snapToGrid ? Math.round(x / this.gridSize) * this.gridSize : x;
                        const snappedY = this.snapToGrid ? Math.round(y / this.gridSize) * this.gridSize : y;
                        
                        let transform = `translate(${snappedX}px, ${snappedY}px)`;
                        if (table && table.shape === 'diamond') {
                            transform += ' rotate(45deg)';
                        }
                        
                        element.style.transform = transform;
                        element.setAttribute('data-x', snappedX);
                        element.setAttribute('data-y', snappedY);
                        
                        this.updateTablePosition(tableId, 
                            parseInt(element.style.left) + snappedX,
                            parseInt(element.style.top) + snappedY
                        );
                    },
                    end: () => {
                        element.classList.remove('dragging');
                    }
                }
            })
            .resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    move: (event) => {
                        let { x, y } = event.target.dataset;
                        
                        x = (parseFloat(x) || 0) + event.deltaRect.left;
                        y = (parseFloat(y) || 0) + event.deltaRect.top;
                        
                        const width = Math.max(this.minTableSize, Math.min(this.maxTableSize, event.rect.width));
                        const height = Math.max(this.minTableSize, Math.min(this.maxTableSize, event.rect.height));
                        
                        Object.assign(event.target.style, {
                            width: width + 'px',
                            height: height + 'px'
                        });
                        
                        let transform = `translate(${x}px, ${y}px)`;
                        if (table && table.shape === 'diamond') {
                            transform += ' rotate(45deg)';
                        }
                        
                        event.target.style.transform = transform;
                        
                        Object.assign(event.target.dataset, { x, y });
                        
                        // Update table data
                        if (table) {
                            table.width = width;
                            table.height = height;
                            table.x = parseInt(event.target.style.left) + x;
                            table.y = parseInt(event.target.style.top) + y;
                        }
                    }
                }
            })
            .on('tap', (event) => {
                if (!event.target.closest('.resize-handle')) {
                    this.handleElementClick(event.target, 'table');
                }
            });
    },

    makeSeatInteractive(element) {
        const seatId = element.dataset.seatId;
        const seat = this.seats.find(s => s.id === seatId);
        
        // Make draggable
        interact(element)
            .draggable({
                listeners: {
                    start: () => {
                        element.classList.add('dragging');
                    },
                    move: (event) => {
                        const x = (parseFloat(element.getAttribute('data-x')) || 0) + event.dx;
                        const y = (parseFloat(element.getAttribute('data-y')) || 0) + event.dy;
                        
                        const snappedX = this.snapToGrid ? Math.round(x / this.gridSize) * this.gridSize : x;
                        const snappedY = this.snapToGrid ? Math.round(y / this.gridSize) * this.gridSize : y;
                        
                        element.style.transform = `translate(${snappedX}px, ${snappedY}px)`;
                        element.setAttribute('data-x', snappedX);
                        element.setAttribute('data-y', snappedY);
                        
                        this.updateSeatPosition(seatId,
                            parseInt(element.style.left) + snappedX,
                            parseInt(element.style.top) + snappedY
                        );
                    },
                    end: () => {
                        element.classList.remove('dragging');
                    }
                }
            })
            .resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    move: (event) => {
                        let { x, y } = event.target.dataset;
                        
                        x = (parseFloat(x) || 0) + event.deltaRect.left;
                        y = (parseFloat(y) || 0) + event.deltaRect.top;
                        
                        const size = Math.max(this.minSeatSize, Math.min(this.maxSeatSize, Math.min(event.rect.width, event.rect.height)));
                        
                        Object.assign(event.target.style, {
                            width: size + 'px',
                            height: size + 'px',
                            transform: `translate(${x}px, ${y}px)`
                        });
                        
                        Object.assign(event.target.dataset, { x, y });
                        
                        // Update seat data
                        if (seat) {
                            seat.width = size;
                            seat.height = size;
                            seat.x = parseInt(event.target.style.left) + x;
                            seat.y = parseInt(event.target.style.top) + y;
                        }
                    }
                }
            })
            .on('tap', (event) => {
                if (!event.target.closest('.resize-handle')) {
                    this.handleElementClick(event.target, 'seat');
                }
            });
    },

    handleElementClick(element, type) {
        if (this.currentTool === 'delete') {
            if (type === 'table') {
                this.deleteTable(element.dataset.tableId);
            } else {
                this.deleteSeat(element.dataset.seatId);
            }
        } else if (this.currentTool === 'select') {
            if (type === 'seat' && this.sellingMode === 'per_seat' && !element.dataset.seatId.includes('table')) {
                this.toggleSeatType(element.dataset.seatId);
            }
            this.toggleSelection(element);
        }
    },

    updateTablePosition(tableId, x, y) {
        const table = this.tables.find(t => t.id === tableId);
        if (table) {
            table.x = x;
            table.y = y;
            
            // In per_table mode, there are no individual seats to move
            console.log('Table position updated:', tableId, 'to', x, y);
        }
    },

    updateSeatPosition(seatId, x, y) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat) {
            seat.x = x;
            seat.y = y;
        }
    },

    toggleSeatType(seatId) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat) {
            seat.type = seat.type === 'VIP' ? 'Regular' : 'VIP';
            
            const element = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (element) {
                element.className = element.className.replace(/\b(vip|regular)\b/g, '');
                element.classList.add(seat.type.toLowerCase());
            }
            
            this.updateStatistics();
        }
    },

    deleteTable(tableId) {
        // Remove table
        this.tables = this.tables.filter(t => t.id !== tableId);
        
        // Remove DOM element
        document.querySelector(`[data-table-id="${tableId}"]`)?.remove();
        
        // In per_table mode, no individual seats to remove
        console.log('Table deleted:', tableId);
        
        this.updateStatistics();
    },

    deleteSeat(seatId) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat && seat.table_id) {
            // Remove from table seats
            const table = this.tables.find(t => t.id === seat.table_id);
            if (table) {
                table.seats = table.seats.filter(s => s.id !== seatId);
            }
        }
        
        // Remove from main seats array
        this.seats = this.seats.filter(s => s.id !== seatId);
        
        // Remove DOM element
        document.querySelector(`[data-seat-id="${seatId}"]`)?.remove();
        
        this.updateStatistics();
    },

    toggleSelection(element) {
        if (element.classList.contains('selected')) {
            element.classList.remove('selected');
            this.selectedElements = this.selectedElements.filter(el => el !== element);
        } else {
            element.classList.add('selected');
            this.selectedElements.push(element);
        }
    },

    clearSelection() {
        this.selectedElements.forEach(el => el.classList.remove('selected'));
        this.selectedElements = [];
    },

    // Quick layout generators
    createGridLayout(rows, cols) {
        if (!confirm(`Buat layout grid ${rows}x${cols}? Ini akan menghapus semua elemen yang ada.`)) return;

        this.clearAll();
        
        const startX = 50;
        const startY = 50;
        const seatSpacing = 50;
        
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const x = startX + (col * seatSpacing);
                const y = startY + (row * seatSpacing);
                this.createSeat('Regular', x, y);
            }
        }
    },

    createTheaterLayout() {
        if (!confirm('Buat layout theater? Ini akan menghapus semua elemen yang ada.')) return;

        this.clearAll();
        
        const centerX = 400;
        const startY = 100;
        const rowSpacing = 50;
        
        for (let row = 0; row < 12; row++) {
            const seatsInRow = Math.min(20, 10 + row);
            const rowWidth = (seatsInRow - 1) * 45;
            const startX = centerX - (rowWidth / 2);
            
            for (let seat = 0; seat < seatsInRow; seat++) {
                const x = startX + (seat * 45);
                const y = startY + (row * rowSpacing);
                const type = row < 3 ? 'VIP' : 'Regular';
                this.createSeat(type, x, y);
            }
        }
    },

    createRestaurantLayout() {
        if (!confirm('Buat layout restaurant? Ini akan menghapus semua elemen yang ada.')) return;

        this.clearAll();
        
        const shapes = ['square', 'circle', 'rectangle'];
        const positions = [
            {x: 100, y: 100}, {x: 300, y: 100}, {x: 500, y: 100}, {x: 700, y: 100},
            {x: 100, y: 200}, {x: 300, y: 200}, {x: 500, y: 200}, {x: 700, y: 200},
            {x: 100, y: 300}, {x: 300, y: 300}, {x: 500, y: 300}, {x: 700, y: 300},
            {x: 100, y: 400}, {x: 300, y: 400}, {x: 500, y: 400}, {x: 700, y: 400},
            {x: 200, y: 450}, {x: 400, y: 450}, {x: 600, y: 450}, {x: 800, y: 450}
        ];
        
        positions.forEach((pos, index) => {
            const shape = shapes[index % shapes.length];
            this.createTable(pos.x, pos.y, shape);
        });
    },

    createBanquetLayout() {
        if (!confirm('Buat layout banquet? Ini akan menghapus semua elemen yang ada.')) return;

        this.clearAll();
        
        const centerX = 400;
        const centerY = 300;
        const shapes = ['circle', 'square', 'rectangle', 'diamond'];
        
        // Inner circle - 10 tables
        for (let i = 0; i < 10; i++) {
            const angle = (2 * Math.PI * i) / 10;
            const x = centerX + Math.cos(angle) * 150;
            const y = centerY + Math.sin(angle) * 150;
            const shape = shapes[i % shapes.length];
            this.createTable(x, y, shape);
        }
        
        // Outer circle - 20 tables
        for (let i = 0; i < 20; i++) {
            const angle = (2 * Math.PI * i) / 20;
            const x = centerX + Math.cos(angle) * 250;
            const y = centerY + Math.sin(angle) * 250;
            const shape = shapes[i % shapes.length];
            this.createTable(x, y, shape);
        }
    },

    clearAll() {
        console.log('🧹 Clearing all data...');
        
        this.seats = [];
        this.tables = [];
        this.seatCounter = 0;
        this.tableCounter = 0;
        this.clearSelection();
        
        const container = document.getElementById('elements-container');
        if (container) {
            container.innerHTML = '';
            console.log('🗑️ Canvas cleared');
        }
        
        this.updateStatistics();
        
        console.log('✅ All data cleared successfully');
    },

    updateStatistics() {
        const totalTables = this.tables.length;

        // Get prices
        const regularPrice = parseInt(document.getElementById('regular_price')?.value || '150000');
        const vipPrice = parseInt(document.getElementById('vip_price')?.value || '300000');
        const tablePrice = parseInt(document.getElementById('table_price')?.value || '500000');

        let estimatedRevenue = 0;
        let statisticsHTML = '';
        
        if (this.sellingMode === 'per_table') {
            // For per_table mode, only count tables and total capacity
            let totalCapacity = 0;
            this.tables.forEach(table => {
                totalCapacity += table.capacity || 4;
            });

            estimatedRevenue = totalTables * tablePrice;
            
            // Count tables by shape
            const shapeCount = {};
            this.tables.forEach(table => {
                const shape = table.shape || 'square';
                shapeCount[shape] = (shapeCount[shape] || 0) + 1;
            });
            
            statisticsHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Meja:</span>
                    <span class="font-medium">${totalTables}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Kapasitas:</span>
                    <span class="font-medium">${totalCapacity} orang</span>
                </div>
            `;
            
            // Add shape breakdown
            if (Object.keys(shapeCount).length > 0) {
                statisticsHTML += '<hr class="my-2"><div class="text-xs text-gray-500">Bentuk Meja:</div>';
                Object.entries(shapeCount).forEach(([shape, count]) => {
                    const shapeLabel = {
                        'square': 'Persegi',
                        'circle': 'Bulat',
                        'rectangle': 'Persegi Panjang',
                        'diamond': 'Wajik'
                    }[shape] || shape;
                    
                    statisticsHTML += `
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">${shapeLabel}:</span>
                            <span class="font-medium">${count}</span>
                        </div>
                    `;
                });
            }
        } else {
            // For per_seat mode, count individual seats
            const regularCount = this.seats.filter(s => s.type === 'Regular').length;
            const vipCount = this.seats.filter(s => s.type === 'VIP').length;
            const totalSeats = this.seats.length;

            estimatedRevenue = (regularCount * regularPrice) + (vipCount * vipPrice);
            statisticsHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Kursi:</span>
                    <span class="font-medium">${totalSeats}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kursi Regular:</span>
                    <span class="font-medium">${regularCount}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kursi VIP:</span>
                    <span class="font-medium">${vipCount}</span>
                </div>
            `;
        }

        statisticsHTML += `
            <hr class="my-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Est. Revenue:</span>
                <span class="font-medium text-green-600">Rp ${estimatedRevenue.toLocaleString('id-ID')}</span>
            </div>
        `;

        const container = document.getElementById('statistics');
        if (container) container.innerHTML = statisticsHTML;
    },

    generateSeatRow(y) {
        const rowIndex = Math.floor(y / 50);
        return String.fromCharCode(65 + Math.min(rowIndex, 25));
    },

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            
            switch (e.key.toLowerCase()) {
                case 'delete':
                    this.selectedElements.forEach(element => {
                        if (element.dataset.seatId) {
                            this.deleteSeat(element.dataset.seatId);
                        } else if (element.dataset.tableId) {
                            this.deleteTable(element.dataset.tableId);
                        }
                    });
                    this.selectedElements = [];
                    break;
                    
                case 'escape':
                    this.clearSelection();
                    break;
                    
                case 'a':
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        document.querySelectorAll('.seat-element, .table-element').forEach(el => {
                            el.classList.add('selected');
                            this.selectedElements.push(el);
                        });
                    }
                    break;
                    
                case 'r':
                    e.preventDefault();
                    this.setTool('select');
                    break;
            }
        });
    },

    // Validation method to check if layout has valid data
    validateLayout() {
        console.log('🔍 Validating layout data...');
        
        const errors = [];
        
        if (this.sellingMode === 'per_table') {
            if (this.tables.length === 0) {
                errors.push('Layout harus memiliki minimal satu meja');
            }
            
            // Validate each table
            this.tables.forEach((table, index) => {
                if (!table.x || !table.y) {
                    errors.push(`Meja ${index + 1} tidak memiliki posisi yang valid`);
                }
                if (!table.capacity || table.capacity < 2 || table.capacity > 12) {
                    errors.push(`Meja ${index + 1} kapasitas tidak valid (2-12 orang)`);
                }
                if (!table.shape) {
                    errors.push(`Meja ${index + 1} tidak memiliki bentuk`);
                }
            });
        } else {
            if (this.seats.length === 0) {
                errors.push('Layout harus memiliki minimal satu kursi');
            }
            
            // Validate each seat
            this.seats.forEach((seat, index) => {
                if (!seat.x || !seat.y) {
                    errors.push(`Kursi ${index + 1} tidak memiliki posisi yang valid`);
                }
                if (!seat.type || !['Regular', 'VIP'].includes(seat.type)) {
                    errors.push(`Kursi ${index + 1} tipe tidak valid`);
                }
            });
        }
        
        if (errors.length > 0) {
            console.error('❌ Validation errors:', errors);
            alert('Error validasi layout:\n\n' + errors.join('\n'));
            return false;
        }
        
        console.log('✅ Layout validation passed');
        return true;
    },

    // Enhanced sync with validation
    syncWithLivewire() {
        console.log('📡 Syncing data with Livewire...');
        
        // Validate first
        if (!this.validateLayout()) {
            console.error('❌ Cannot sync - validation failed');
            return false;
        }
        
        console.log('📊 Data to sync:');
        console.log('- Selling mode:', this.sellingMode);
        console.log('- Seats count:', this.seats.length);
        console.log('- Tables count:', this.tables.length);
        
        if (window.Livewire && @this) {
            try {
                // Set the data based on selling mode
                if (this.sellingMode === 'per_table') {
                    @this.set('tables', this.tables);
                    @this.set('custom_seats', []); // No individual seats in per_table mode
                    console.log('✅ Tables synced:', this.tables.length);
                } else {
                    @this.set('custom_seats', this.seats);
                    @this.set('tables', []); // No tables in per_seat mode
                    console.log('✅ Seats synced:', this.seats.length);
                }
                
                // Also sync the selling mode
                @this.set('selling_mode', this.sellingMode);
                
                console.log('🎯 Data sync completed successfully');
                return true;
                
            } catch (error) {
                console.error('❌ Error syncing data:', error);
                alert('Error sinkronisasi data: ' + error.message);
                return false;
            }
        } else {
            console.error('❌ Livewire not available for sync');
            alert('Livewire tidak tersedia untuk sinkronisasi data');
            return false;
        }
    },

    // Background image functions
    handleBackgroundUpload(input) {
        const file = input.files[0];
        if (!file) return;

        console.log('📷 Uploading background image:', file.name);

        const reader = new FileReader();
        reader.onload = (e) => {
            this.setBackgroundImage(e.target.result);
        };
        reader.readAsDataURL(file);
    },

    setBackgroundImage(src) {
        const container = document.getElementById('background-image-container');
        if (!container) return;

        container.innerHTML = `<img src="${src}" alt="Background" style="opacity: ${this.backgroundOpacity};">`;
        console.log('✅ Background image set');
    },

    removeBackground() {
        const container = document.getElementById('background-image-container');
        if (container) {
            container.innerHTML = '';
        }
        
        // Reset file input
        const input = document.getElementById('background_image');
        if (input) {
            input.value = '';
        }
        
        console.log('🗑️ Background image removed');
    },

    toggleBackgroundOpacity() {
        const img = document.querySelector('#background-image-container img');
        if (!img) return;

        this.backgroundOpacity = this.backgroundOpacity === 0.3 ? 0.6 : 0.3;
        img.style.opacity = this.backgroundOpacity;
        
        console.log('🔄 Background opacity changed to:', this.backgroundOpacity);
    },

    // Zoom functions
    zoomIn() {
        this.zoomLevel = Math.min(2, this.zoomLevel + 0.1);
        this.updateZoom();
    },

    zoomOut() {
        this.zoomLevel = Math.max(0.5, this.zoomLevel - 0.1);
        this.updateZoom();
    },

    resetZoom() {
        this.zoomLevel = 1;
        this.updateZoom();
    },

    updateZoom() {
        const container = document.getElementById('zoom-container');
        const levelDisplay = document.getElementById('zoom-level');
        
        if (container) {
            container.style.transform = `scale(${this.zoomLevel})`;
        }
        
        if (levelDisplay) {
            levelDisplay.textContent = Math.round(this.zoomLevel * 100) + '%';
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM Content Loaded');
    
    // Small delay to ensure everything is ready
    setTimeout(() => {
        if (window.SeatLayoutManager) {
            console.log('🔄 Starting Enhanced SeatLayoutManager initialization...');
            SeatLayoutManager.init();
        } else {
            console.error('❌ SeatLayoutManager not found!');
        }
    }, 100);
});

// Initialize when modal opens
document.addEventListener('livewire:initialized', () => {
    console.log('⚡ Livewire initialized');
    
    // Re-initialize when Livewire updates DOM
    Livewire.hook('morph.updated', () => {
        console.log('🔄 Livewire DOM updated');
        setTimeout(() => {
            if (window.SeatLayoutManager && document.getElementById('seat-canvas')) {
                console.log('🔄 Re-initializing Enhanced SeatLayoutManager...');
                SeatLayoutManager.init();
            }
        }, 200);
    });
});

// Enhanced form submission handler with validation
document.addEventListener('submit', function(e) {
    const form = e.target.closest('form');
    if (form && window.SeatLayoutManager) {
        console.log('📝 Form submission intercepted...');
        
        // Prevent default submission temporarily
        e.preventDefault();
        
        console.log('🔍 Pre-submission validation...');
        console.log('📊 Current state:');
        console.log('- Selling mode:', SeatLayoutManager.sellingMode);
        console.log('- Seats count:', SeatLayoutManager.seats.length);
        console.log('- Tables count:', SeatLayoutManager.tables.length);
        
        // Validate and sync data
        const syncSuccess = SeatLayoutManager.syncWithLivewire();
        
        if (syncSuccess) {
            console.log('✅ Validation passed, continuing submission...');
            
            // Submit the form programmatically
            setTimeout(() => {
                console.log('📨 Submitting form now...');
                form.submit();
            }, 100);
        } else {
            console.error('❌ Form submission cancelled due to validation/sync failure');
        }
        
        return false; // Prevent default form submission
    }
});

// Enhanced debug helper with comprehensive checks
window.debugSeatManager = function() {
    console.log('🐛 COMPREHENSIVE DEBUG INFO:');
    console.log('='.repeat(50));
    
    // 1. Manager State
    console.log('📊 MANAGER STATE:');
    console.log('- Current tool:', SeatLayoutManager.currentTool);
    console.log('- Selling mode:', SeatLayoutManager.sellingMode);
    console.log('- Table shape:', SeatLayoutManager.currentTableShape);
    console.log('- Seats count:', SeatLayoutManager.seats.length);
    console.log('- Tables count:', SeatLayoutManager.tables.length);
    
    // 2. DOM Elements
    console.log('🎨 DOM ELEMENTS:');
    console.log('- Canvas exists:', !!document.getElementById('seat-canvas'));
    console.log('- Elements container exists:', !!document.getElementById('elements-container'));
    console.log('- Form exists:', !!document.querySelector('form'));
    console.log('- Save button exists:', !!document.getElementById('save-button'));
    
    // 3. Livewire Connection
    console.log('⚡ LIVEWIRE CONNECTION:');
    if (window.Livewire && @this) {
        console.log('- Livewire OK: ✅');
        try {
            console.log('- Component selling_mode:', @this.get('selling_mode'));
            console.log('- Component custom_seats count:', (@this.get('custom_seats') || []).length);
            console.log('- Component tables count:', (@this.get('tables') || []).length);
            console.log('- Component layout_name:', @this.get('layout_name'));
        } catch (e) {
            console.error('- Error getting Livewire data:', e);
        }
    } else {
        console.log('- Livewire OK: ❌');
    }
    
    // 4. Form Data
    console.log('📝 FORM DATA:');
    const layoutName = document.getElementById('layout_name');
    const tablePrice = document.getElementById('table_price');
    const regularPrice = document.getElementById('regular_price');
    console.log('- Layout name input:', layoutName ? layoutName.value : 'NOT FOUND');
    console.log('- Table price input:', tablePrice ? tablePrice.value : 'NOT FOUND');
    console.log('- Regular price input:', regularPrice ? regularPrice.value : 'NOT FOUND');
    
    // 5. Data Details
    console.log('📋 DATA DETAILS:');
    console.log('- Seats data:', SeatLayoutManager.seats);
    console.log('- Tables data:', SeatLayoutManager.tables);
    
    // 6. Canvas State
    console.log('🎨 CANVAS STATE:');
    const canvas = document.getElementById('seat-canvas');
    const container = document.getElementById('elements-container');
    if (canvas && container) {
        console.log('- Canvas dimensions:', canvas.clientWidth, 'x', canvas.clientHeight);
        console.log('- Elements in container:', container.children.length);
        console.log('- Canvas classes:', canvas.className);
    }
    
    console.log('='.repeat(50));
    
    // Trigger server-side debug
    if (window.Livewire && @this) {
        @this.call('debugSystemStatus').then((result) => {
            console.log('🔍 SERVER DEBUG RESULT:', result);
        }).catch((error) => {
            console.error('❌ Server debug failed:', error);
        });
    }
};

// Enhanced sync function with validation
window.forceSyncData = function() {
    console.log('🔄 FORCE SYNC DATA...');
    
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        return false;
    }

    if (!window.Livewire || !@this) {
        console.error('❌ Livewire not available');
        return false;
    }

    try {
        // Prepare data for sync
        const syncData = {
            sellingMode: SeatLayoutManager.sellingMode,
            layoutName: document.getElementById('layout_name')?.value || '',
            seats: SeatLayoutManager.seats,
            tables: SeatLayoutManager.tables,
            vipPrice: parseInt(document.getElementById('vip_price')?.value || '300000'),
            regularPrice: parseInt(document.getElementById('regular_price')?.value || '150000'),
            tablePrice: parseInt(document.getElementById('table_price')?.value || '500000'),
            tableCapacity: parseInt(document.getElementById('table_capacity')?.value || '4')
        };
        
        console.log('📊 Data to sync:', syncData);
        
        // Call Livewire method
        @this.call('forceSyncFromJs', syncData).then((result) => {
            console.log('✅ Force sync result:', result);
            if (result.success) {
                alert('✅ Data berhasil di-sync!');
            } else {
                alert('❌ Sync gagal: ' + result.message);
            }
        }).catch((error) => {
            console.error('❌ Force sync error:', error);
            alert('❌ Error sync: ' + error.message);
        });
        
        return true;
        
    } catch (error) {
        console.error('❌ Force sync failed:', error);
        alert('❌ Force sync failed: ' + error.message);
        return false;
    }
};

// Test save with current data
window.testSaveLayout = function() {
    console.log('🧪 TESTING SAVE LAYOUT...');
    
    if (!SeatLayoutManager.validateLayout()) {
        console.error('❌ Validation failed, cannot test save');
        return;
    }
    
    // Create test data if none exists
    if (SeatLayoutManager.sellingMode === 'per_table' && SeatLayoutManager.tables.length === 0) {
        console.log('📝 Creating test table data...');
        SeatLayoutManager.createTable(100, 100, 'square');
    } else if (SeatLayoutManager.sellingMode === 'per_seat' && SeatLayoutManager.seats.length === 0) {
        console.log('📝 Creating test seat data...');
        SeatLayoutManager.createSeat('Regular', 100, 100);
        SeatLayoutManager.createSeat('VIP', 150, 100);
    }
    
    // Set layout name if empty
    const layoutNameInput = document.getElementById('layout_name');
    if (layoutNameInput && !layoutNameInput.value) {
        layoutNameInput.value = 'Test Layout ' + new Date().toLocaleString();
        // Trigger Livewire update
        layoutNameInput.dispatchEvent(new Event('input'));
    }
    
    // Sync data
    const syncSuccess = SeatLayoutManager.syncWithLivewire();
    
    if (syncSuccess) {
        console.log('✅ Test data ready, calling save...');
        setTimeout(() => {
            if (window.Livewire && @this) {
                @this.call('saveLayout').then(() => {
                    console.log('✅ Test save completed');
                }).catch((error) => {
                    console.error('❌ Test save failed:', error);
                });
            }
        }, 500);
    } else {
        console.error('❌ Test save cancelled due to sync failure');
    }
};

// Manual save with full validation
window.manualSaveLayout = function() {
    console.log('💾 MANUAL SAVE LAYOUT...');
    
    // Run full debug first
    debugSeatManager();
    
    // Validate layout
    if (!SeatLayoutManager.validateLayout()) {
        console.error('❌ Layout validation failed');
        return;
    }
    
    // Check required fields
    const layoutName = document.getElementById('layout_name')?.value;
    if (!layoutName || layoutName.trim() === '') {
        alert('❌ Nama layout harus diisi!');
        return;
    }
    
    // Sync data with detailed logging
    console.log('📡 Syncing data before save...');
    const syncSuccess = SeatLayoutManager.syncWithLivewire();
    
    if (!syncSuccess) {
        console.error('❌ Manual save cancelled due to sync failure');
        return;
    }
    
    // Wait a bit then save
    setTimeout(() => {
        console.log('💾 Executing manual save...');
        if (window.Livewire && @this) {
            @this.call('saveLayout')
                .then((result) => {
                    console.log('✅ Manual save successful:', result);
                    alert('✅ Layout berhasil disimpan!');
                })
                .catch((error) => {
                    console.error('❌ Manual save failed:', error);
                    alert('❌ Save gagal: ' + (error.message || 'Unknown error'));
                });
        }
    }, 1000);
};

// Test with dummy data
window.testWithDummyData = function() {
    console.log('🧪 TESTING WITH DUMMY DATA...');
    
    if (window.Livewire && @this) {
        @this.call('testSaveWithDummyData')
            .then(() => {
                console.log('✅ Dummy data test completed');
            })
            .catch((error) => {
                console.error('❌ Dummy data test failed:', error);
            });
    }
};

// Check system status
window.checkSystemStatus = function() {
    console.log('🔍 CHECKING SYSTEM STATUS...');
    
    if (window.Livewire && @this) {
        @this.call('fullSystemCheck')
            .then((debugInfo) => {
                console.log('📊 SYSTEM STATUS:', debugInfo);
                
                // Display in alert for quick view
                const summary = `
                DATABASE:
                    - Layouts: ${debugInfo.database.seat_layouts_count}
                    - Seats: ${debugInfo.database.seats_count}  
                    - Tables: ${debugInfo.database.tables_count}

                    CURRENT STATE:
                    - Mode: ${debugInfo.current_state.selling_mode}
                    - Name: ${debugInfo.current_state.layout_name}
                    - Seats: ${debugInfo.current_state.custom_seats}
                    - Tables: ${debugInfo.current_state.tables}

                LOADED LAYOUTS: ${debugInfo.loaded_layouts.count}
                `;
                
                alert('📊 SYSTEM STATUS:\n' + summary);
            })
            .catch((error) => {
                console.error('❌ System status check failed:', error);
            });
    }
};

// Test save functionality
window.testSaveLayout = function() {
    console.log('🧪 Testing save layout...');
    if (window.SeatLayoutManager) {
        // Create some test data
        SeatLayoutManager.clearAll();
        
        if (SeatLayoutManager.sellingMode === 'per_table') {
            SeatLayoutManager.createTable(100, 100, 'square');
            SeatLayoutManager.createTable(250, 100, 'circle');
        } else {
            SeatLayoutManager.createSeat('Regular', 100, 100);
            SeatLayoutManager.createSeat('VIP', 150, 100);
        }
        
        // Try to sync
        SeatLayoutManager.syncWithLivewire();
        
        console.log('✅ Test data created and synced');
        console.log('Use debugSeatManager() to check the results');
    }
};

// Force sync function
window.forceSyncData = function() {
    console.log('🔄 Force syncing data...');
    if (window.SeatLayoutManager && window.Livewire && @this) {
        try {
            // Manual sync with detailed logging
            const seatsData = SeatLayoutManager.seats;
            const tablesData = SeatLayoutManager.tables;
            const mode = SeatLayoutManager.sellingMode;
            
            console.log('Data to sync:', { seatsData, tablesData, mode });
            
            @this.call('updateLayoutData', seatsData, tablesData);
            console.log('✅ Force sync completed');
            
        } catch (error) {
            console.error('❌ Force sync failed:', error);
        }
    }
};

// Save layout with manual trigger
window.manualSaveLayout = function() {
    console.log('💾 Manual save layout triggered...');
    if (window.SeatLayoutManager && window.Livewire && @this) {
        // Sync data first
        SeatLayoutManager.syncWithLivewire();
        
        // Wait a bit then try to save
        setTimeout(() => {
            try {
                @this.call('saveLayout');
                console.log('✅ Manual save initiated');
            } catch (error) {
                console.error('❌ Manual save failed:', error);
            }
        }, 200);
    }
};

// Test resize function
window.testResize = function() {
    console.log('🧪 Testing resize functionality...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.createSeat('Regular', 100, 100);
        SeatLayoutManager.createTable(200, 200, 'square');
        console.log('✅ Test elements created - try selecting and resizing them');
    }
};

// Test shape function
window.testShapes = function() {
    console.log('🧪 Testing table shapes...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.clearAll();
        SeatLayoutManager.createTable(100, 100, 'square');
        SeatLayoutManager.createTable(250, 100, 'circle');
        SeatLayoutManager.createTable(400, 100, 'rectangle');
        SeatLayoutManager.createTable(550, 100, 'diamond');
        console.log('✅ All table shapes created');
    }
};
</script>
@endpush