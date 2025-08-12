<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Reservasi Event Terbaik
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8">
                    Pilih kursi favorit Anda dengan mudah dan aman
                </p>
                
                <!-- Quick Search -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Cari event, venue, atau kategori..."
                               class="w-full px-6 py-4 rounded-full text-gray-900 text-lg focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <button class="absolute right-2 top-2 bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Filter Bar -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                <!-- Filter Toggle Button (Mobile) -->
                <div class="lg:hidden">
                    <button wire:click="toggleFilters" 
                            class="flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Filter & Urutkan
                    </button>
                </div>

                <!-- Filters (Desktop) -->
                <div class="hidden lg:flex items-center space-x-4 flex-1">
                    
                    <!-- Date Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Event</label>
                        <input wire:model.live="filterDate" 
                               type="date" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Venue Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <select wire:model.live="filterVenue" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue }}">{{ $venue }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    @if($categories->count() > 0)
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select wire:model.live="filterCategory" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Clear Filters -->
                    @if($search || $filterDate || $filterVenue || $filterCategory)
                    <div class="flex-shrink-0">
                        <button wire:click="clearFilters" 
                                class="px-4 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                            Reset Filter
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Sort Options -->
                <div class="hidden lg:flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Urutkan:</span>
                    <button wire:click="sortBy('event_date')" 
                            class="px-3 py-1 text-sm rounded-lg transition-colors
                            {{ $sortBy === 'event_date' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        Tanggal
                        @if($sortBy === 'event_date')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('event_name')" 
                            class="px-3 py-1 text-sm rounded-lg transition-colors
                            {{ $sortBy === 'event_name' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        Nama
                        @if($sortBy === 'event_name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                </div>
            </div>

            <!-- Mobile Filters -->
            @if($showFilters)
            <div class="lg:hidden mt-6 pt-6 border-t space-y-4">
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Event</label>
                    <input wire:model.live="filterDate" 
                           type="date" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Venue Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                    <select wire:model.live="filterVenue" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue }}">{{ $venue }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                @if($categories->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select wire:model.live="filterCategory" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Clear Filters -->
                @if($search || $filterDate || $filterVenue || $filterCategory)
                <div>
                    <button wire:click="clearFilters" 
                            class="w-full px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                        Reset Semua Filter
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Loading State -->
        <div wire:loading class="flex justify-center py-8">
            <div class="flex items-center space-x-2 text-gray-600">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat event...</span>
            </div>
        </div>

        <!-- Events Grid -->
        <div wire:loading.remove>
            @if($events->count() > 0)
                <!-- Results Count -->
                <div class="mb-6">
                    <p class="text-gray-600">
                        Menampilkan {{ $events->count() }} dari {{ $events->total() }} event
                        @if($search || $filterDate || $filterVenue || $filterCategory)
                            yang sesuai dengan filter
                        @endif
                    </p>
                </div>

                <!-- Event Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        
                        <!-- Event Image -->
                        <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                            @if($event->event_image)
                                <img src="{{ Storage::url($event->event_image) }}" 
                                     alt="{{ $event->event_name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white">
                                    <svg class="w-16 h-16 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if(isset($event->event_category) && $event->event_category)
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($event->event_category) }}
                                </span>
                            </div>
                            @elseif(isset($event->category) && $event->category)
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($event->category) }}
                                </span>
                            </div>
                            @endif

                            <!-- Availability Badge -->
                            <div class="absolute top-3 right-3">
                                @if($event->stats['available_seats'] > 0)
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                        {{ $event->stats['available_seats'] }} kursi tersisa
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                        Sold Out
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Event Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $event->event_name }}
                            </h3>
                            
                            <!-- Event Details -->
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                    @if($event->event_time)
                                        • {{ $event->event_time }}
                                    @endif
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->venue }}
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Kapasitas: {{ $event->stats['total_capacity'] }} orang
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="mb-4">
                                @if($event->stats['min_price'] > 0)
                                    @if($event->stats['min_price'] == $event->stats['max_price'])
                                        <div class="text-2xl font-bold text-green-600">
                                            Rp {{ number_format($event->stats['min_price'], 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($event->stats['min_price'], 0, ',', '.') }} - 
                                            Rp {{ number_format($event->stats['max_price'], 0, ',', '.') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-lg font-bold text-gray-600">
                                        Harga belum tersedia
                                    </div>
                                @endif
                                
                                <!-- Selling Mode Info -->
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($event->stats['selling_modes'] as $mode)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                            {{ $mode === 'per_table' ? 'Per Meja' : 'Per Kursi' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button wire:click="goToReservation({{ $event->event_id }})"
                                    class="w-full py-3 px-4 rounded-lg font-semibold transition-all duration-200
                                    {{ $event->stats['available_seats'] > 0 
                                        ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                        : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                                    {{ $event->stats['available_seats'] == 0 ? 'disabled' : '' }}>
                                {{ $event->stats['available_seats'] > 0 ? 'Pilih Kursi' : 'Sold Out' }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $events->links() }}
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">Tidak ada event ditemukan</h3>
                    <p class="text-gray-600 mb-6">
                        @if($search || $filterDate || $filterVenue || $filterCategory)
                            Tidak ada event yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada event yang tersedia untuk saat ini.
                        @endif
                    </p>
                    @if($search || $filterDate || $filterVenue || $filterCategory)
                        <button wire:click="clearFilters" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Reset Filter
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush