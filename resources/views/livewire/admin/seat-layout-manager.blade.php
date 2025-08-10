@push('styles')
<style>
/* Seat and Table Element Styles */
.seat-element, .table-element {
    position: absolute;
    border: 2px solid #ddd;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: move;
    user-select: none;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.seat-element:hover, .table-element:hover {
    border-color: #007cba;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.seat-element.selected, .table-element.selected {
    border-color: #007cba;
    background-color: rgba(0, 124, 186, 0.1);
    box-shadow: 0 0 0 3px rgba(0, 124, 186, 0.3);
}

.seat-element.dragging, .table-element.dragging {
    opacity: 0.8;
    transform: rotate(2deg);
    z-index: 1000;
}

/* Seat Types */
.seat-element.regular {
    background-color: #3b82f6;
    color: white;
}

.seat-element.vip {
    background-color: #f59e0b;
    color: white;
}

/* Table Shapes */
.table-element {
    background-color: #8b5cf6;
    color: white;
}

.table-element.shape-circle {
    border-radius: 50%;
}

.table-element.shape-diamond {
    transform-origin: center;
}

/* Preview Canvas Styles */
.preview-canvas {
    position: relative;
    background-color: #f8fafc;
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    min-height: 200px;
}

.preview-element {
    position: absolute;
    border-radius: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    font-weight: bold;
    color: white;
}

.preview-seat.regular {
    background-color: #3b82f6;
}

.preview-seat.vip {
    background-color: #f59e0b;
}

.preview-table {
    background-color: #8b5cf6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.preview-table.shape-circle {
    border-radius: 50%;
}

.preview-table.shape-diamond {
    transform: rotate(45deg);
}

.preview-table-content {
    transform: rotate(-45deg);
    text-align: center;
}

/* Stage styles */
.stage-preview {
    background-color: #374151;
    color: white;
    text-align: center;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 12px;
    max-width: 200px;
    margin-left: auto;
    margin-right: auto;
}

/* Layout Card Hover Effects */
.layout-card {
    transition: all 0.3s ease;
}

.layout-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Action Button Styles */
.action-btn {
    transition: all 0.2s ease;
    opacity: 0.7;
}

.action-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* Canvas Tool Cursors */
.tool-select { cursor: default; }
.tool-regular { cursor: crosshair; }
.tool-vip { cursor: crosshair; }
.tool-table { cursor: crosshair; }
.tool-delete { cursor: not-allowed; }

/* Tool Button Styles */
.tool-btn.active {
    background-color: #007cba;
    color: white;
    border-color: #007cba;
}

/* Canvas */
#seat-canvas {
    position: relative;
    background-color: #f8fafc;
    border: 2px dashed #e2e8f0;
    min-height: 600px;
    overflow: hidden;
}

/* Background Image */
#background-image-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 0;
    pointer-events: none;
}

#background-image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* Elements Container */
#elements-container {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 600px;
    z-index: 1;
}

/* Statistics Panel */
#statistics {
    background-color: #f8fafc;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
}

/* Mode Toggle */
#mode-toggle button.active {
    background-color: #007cba;
    color: white;
    border-color: #007cba;
}

/* Table Shape Buttons */
.table-shape-btn.active {
    background-color: #8b5cf6;
    color: white;
    border-color: #8b5cf6;
}

/* Animation for new elements */
@keyframes elementCreated {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.seat-element, .table-element {
    animation: elementCreated 0.3s ease-out;
}

/* Resize Handles */
.resize-handles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.resize-handle {
    position: absolute;
    background-color: #007cba;
    border: 1px solid white;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    pointer-events: all;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.seat-element:hover .resize-handle,
.table-element:hover .resize-handle,
.seat-element.selected .resize-handle,
.table-element.selected .resize-handle {
    opacity: 1;
}

/* Resize Handle Positions */
.resize-handle.nw { top: -4px; left: -4px; cursor: nw-resize; }
.resize-handle.ne { top: -4px; right: -4px; cursor: ne-resize; }
.resize-handle.sw { bottom: -4px; left: -4px; cursor: sw-resize; }
.resize-handle.se { bottom: -4px; right: -4px; cursor: se-resize; }
.resize-handle.n { top: -4px; left: 50%; transform: translateX(-50%); cursor: n-resize; }
.resize-handle.s { bottom: -4px; left: 50%; transform: translateX(-50%); cursor: s-resize; }
.resize-handle.w { top: 50%; left: -4px; transform: translateY(-50%); cursor: w-resize; }
.resize-handle.e { top: 50%; right: -4px; transform: translateY(-50%); cursor: e-resize; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .resize-handle {
        width: 12px;
        height: 12px;
    }
    
    .seat-element, .table-element {
        font-size: 11px;
    }
    
    .preview-canvas {
        min-height: 150px;
    }
}
</style>
<style>
    .tool-btn.active {
        @apply border-2 border-indigo-500 bg-indigo-50;
    }
    
    #seat-canvas {
        background-image: 
            linear-gradient(rgba(0,0,0,.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,.1) 1px, transparent 1px);
        background-size: 20px 20px;
        position: relative;
        overflow: hidden;
        cursor: default;
    }

    #background-image-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    #background-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        opacity: 0.3;
    }

    #elements-container {
        position: relative;
        z-index: 10;
    }
    
    .seat-element {
        touch-action: none;
        user-select: none;
        position: absolute;
        min-width: 32px;
        min-height: 32px;
        width: 44px;
        height: 44px;
        border-radius: 4px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        color: white;
        cursor: move;
        z-index: 10;
        border: 2px solid white;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        transition: transform 0.1s, box-shadow 0.1s;
    }
    
    .seat-element:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 100;
    }
    
    .seat-element.vip {
        background-color: #FBBF24; /* yellow-400 */
    }
    
    .seat-element.regular {
        background-color: #60A5FA; /* blue-400 */
    }
    
    .seat-element.selected {
        outline: 2px solid #4F46E5;
        outline-offset: 2px;
    }
    
    .seat-element.dragging {
        z-index: 1000;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    /* Table styles */
    .table-element {
        position: absolute;
        background-color: #8B5CF6; /* purple-500 */
        border: 2px solid white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        z-index: 5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        cursor: move;
        transition: transform 0.1s, box-shadow 0.1s;
        min-width: 80px;
        min-height: 80px;
        width: 120px;
        height: 120px;
    }

    /* Table shape variants */
    .table-element.shape-square {
        border-radius: 8px;
    }

    .table-element.shape-circle {
        border-radius: 50%;
    }

    .table-element.shape-rectangle {
        border-radius: 12px;
        width: 160px;
        height: 100px;
    }

    .table-element.shape-diamond {
        border-radius: 8px;
        transform-origin: center;
        /* Diamond rotation will be applied via transform */
    }

    .table-element.shape-diamond .table-content {
        transform: rotate(-45deg);
    }
    
    .table-element:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        z-index: 100;
    }

    .table-element.shape-diamond:hover {
        transform: rotate(45deg) scale(1.05);
    }
    
    .table-element.selected {
        outline: 3px solid #4F46E5;
        outline-offset: 2px;
    }
    
    .table-element.dragging {
        z-index: 1000;
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .table-element.shape-diamond.dragging {
        transform: rotate(45deg) scale(1.1);
    }
    
    .table-number {
        font-size: 14px;
        margin-bottom: 2px;
    }
    
    .table-capacity {
        font-size: 10px;
        opacity: 0.9;
    }
    
    /* Seat styles for table seats */
    .seat-element.table-seat {
        border-style: dashed;
        width: 32px;
        height: 32px;
        font-size: 10px;
    }

    /* Resize handles */
    .resize-handles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .selected .resize-handles {
        opacity: 1;
        pointer-events: all;
    }

    .resize-handle {
        position: absolute;
        background: #4F46E5;
        border: 1px solid white;
        border-radius: 2px;
        width: 8px;
        height: 8px;
        z-index: 1001;
    }

    .resize-handle.nw { top: -4px; left: -4px; cursor: nw-resize; }
    .resize-handle.ne { top: -4px; right: -4px; cursor: ne-resize; }
    .resize-handle.sw { bottom: -4px; left: -4px; cursor: sw-resize; }
    .resize-handle.se { bottom: -4px; right: -4px; cursor: se-resize; }
    .resize-handle.n { top: -4px; left: 50%; transform: translateX(-50%); cursor: n-resize; }
    .resize-handle.s { bottom: -4px; left: 50%; transform: translateX(-50%); cursor: s-resize; }
    .resize-handle.w { top: 50%; left: -4px; transform: translateY(-50%); cursor: w-resize; }
    .resize-handle.e { top: 50%; right: -4px; transform: translateY(-50%); cursor: e-resize; }

    /* Ensure all elements are properly positioned */
    .seat-element,
    .table-element {
        position: absolute !important;
    }

    /* Mode toggle styles */
    .mode-toggle {
        display: inline-flex;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
        padding: 0.25rem;
    }

    .mode-toggle button {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        background: none;
        cursor: pointer;
    }

    .mode-toggle button.active {
        background-color: white;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        color: #4f46e5;
    }

    .mode-toggle button:not(.active) {
        color: #6b7280;
    }

    /* Table Shape Selector */
    .table-shape-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .table-shape-btn {
        padding: 0.75rem;
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        text-align: center;
    }

    .table-shape-btn:hover {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }

    .table-shape-btn.active {
        border-color: #4f46e5;
        background-color: #e0e7ff;
    }

    .table-shape-preview {
        width: 24px;
        height: 24px;
        background-color: #8b5cf6;
        margin: 0 auto;
    }

    .table-shape-preview.square {
        border-radius: 4px;
    }

    .table-shape-preview.circle {
        border-radius: 50%;
    }

    .table-shape-preview.rectangle {
        width: 32px;
        height: 20px;
        border-radius: 6px;
    }

    .table-shape-preview.diamond {
        transform: rotate(45deg);
        border-radius: 4px;
    }

    /* Canvas cursor states */
    #seat-canvas.tool-regular,
    #seat-canvas.tool-vip,
    #seat-canvas.tool-table {
        cursor: crosshair;
    }
    
    #seat-canvas.tool-delete {
        cursor: not-allowed;
    }
    
    #seat-canvas.tool-select {
        cursor: default;
    }

    /* Selection rectangle */
    .selection-rect {
        position: absolute;
        border: 2px dashed #4F46E5;
        background: rgba(79, 70, 229, 0.1);
        pointer-events: none;
        z-index: 1000;
    }

    /* Zoom controls */
    .zoom-controls {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 6px;
        padding: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 100;
    }

    .zoom-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .zoom-btn:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .zoom-level {
        padding: 0 8px;
        font-size: 12px;
        color: #6b7280;
        min-width: 40px;
        text-align: center;
    }

    .zoom-container {
        transform-origin: 0 0;
        transition: transform 0.2s ease;
    }
</style>
@endpush

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" wire:init="loadSeatLayouts">
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
                        <span class="text-sm text-gray-600">Enhanced Seat Layout Manager</span>
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
        </div>

        <!-- Seat Layouts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($seatLayouts as $layout)
                <div class="layout-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                    <!-- Layout Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $layout['layout_name'] }}</h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    @php
                                        $config = $layout['layout_config'];
                                        $sellingMode = $config['selling_mode'] ?? 'per_seat';
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $sellingMode === 'per_table' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $sellingMode === 'per_table' ? 'Per Meja' : 'Per Kursi' }}
                                    </span>
                                    
                                    @if($sellingMode === 'per_table')
                                        <span class="text-sm text-gray-500">
                                            {{ count($config['tables'] ?? []) }} meja
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">
                                            {{ count($config['custom_seats'] ?? []) }} kursi
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="editLayout({{ $layout['layout_id'] }})" 
                                        class="action-btn text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
                                        title="Edit Layout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteLayout({{ $layout['layout_id'] }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus layout ini?"
                                        class="action-btn text-red-600 hover:text-red-800 transition-colors duration-200"
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
                            
                            <!-- Stage -->
                            <div class="stage-preview">
                                🎭 PANGGUNG
                            </div>
                            
                            <!-- Preview Canvas -->
                            <div class="preview-canvas">
                                @if($sellingMode === 'per_table')
                                    <!-- Table Mode Preview -->
                                    @php
                                        $tables = $config['tables'] ?? [];
                                        $maxX = 0;
                                        $maxY = 0;
                                        $minX = PHP_INT_MAX;
                                        $minY = PHP_INT_MAX;
                                        
                                        foreach ($tables as $table) {
                                            $maxX = max($maxX, ($table['x'] ?? 0) + ($table['width'] ?? 120));
                                            $maxY = max($maxY, ($table['y'] ?? 0) + ($table['height'] ?? 120));
                                            $minX = min($minX, $table['x'] ?? 0);
                                            $minY = min($minY, $table['y'] ?? 0);
                                        }
                                        
                                        $scaleX = count($tables) > 0 ? 280 / max(1, $maxX - $minX) : 1;
                                        $scaleY = count($tables) > 0 ? 160 / max(1, $maxY - $minY) : 1;
                                        $scale = min($scaleX, $scaleY, 1);
                                    @endphp
                                    
                                    @foreach($tables as $table)
                                        @php
                                            $x = (($table['x'] ?? 0) - $minX) * $scale + 10;
                                            $y = (($table['y'] ?? 0) - $minY) * $scale + 20;
                                            $width = ($table['width'] ?? 120) * $scale;
                                            $height = ($table['height'] ?? 120) * $scale;
                                            $shape = $table['shape'] ?? 'square';
                                            $capacity = $table['capacity'] ?? 4;
                                            $number = $table['number'] ?? 'T' . ($loop->index + 1);
                                        @endphp
                                        
                                        <div class="preview-element preview-table shape-{{ $shape }}"
                                             style="left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px;">
                                            @if($shape === 'diamond')
                                                <div class="preview-table-content">
                                                    <div style="font-size: 6px;">{{ $number }}</div>
                                                    <div style="font-size: 4px;">{{ $capacity }}p</div>
                                                </div>
                                            @else
                                                <div style="font-size: 6px;">{{ $number }}</div>
                                                <div style="font-size: 4px;">{{ $capacity }}p</div>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if(empty($tables))
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                            Belum ada meja
                                        </div>
                                    @endif
                                @else
                                    <!-- Seat Mode Preview -->
                                    @php
                                        $customSeats = $config['custom_seats'] ?? [];
                                        $vipCount = collect($customSeats)->where('type', 'VIP')->count();
                                        $regularCount = collect($customSeats)->where('type', 'Regular')->count();
                                        
                                        $maxX = 0;
                                        $maxY = 0;
                                        $minX = PHP_INT_MAX;
                                        $minY = PHP_INT_MAX;
                                        
                                        foreach ($customSeats as $seat) {
                                            $maxX = max($maxX, ($seat['x'] ?? 0) + ($seat['width'] ?? 44));
                                            $maxY = max($maxY, ($seat['y'] ?? 0) + ($seat['height'] ?? 44));
                                            $minX = min($minX, $seat['x'] ?? 0);
                                            $minY = min($minY, $seat['y'] ?? 0);
                                        }
                                        
                                        $scaleX = count($customSeats) > 0 ? 280 / max(1, $maxX - $minX) : 1;
                                        $scaleY = count($customSeats) > 0 ? 160 / max(1, $maxY - $minY) : 1;
                                        $scale = min($scaleX, $scaleY, 1);
                                    @endphp
                                    
                                    @foreach($customSeats as $seat)
                                        @php
                                            $x = (($seat['x'] ?? 0) - $minX) * $scale + 10;
                                            $y = (($seat['y'] ?? 0) - $minY) * $scale + 20;
                                            $width = ($seat['width'] ?? 44) * $scale;
                                            $height = ($seat['height'] ?? 44) * $scale;
                                            $type = strtolower($seat['type'] ?? 'regular');
                                            $number = $seat['number'] ?? $seat['id'] ?? $loop->index + 1;
                                        @endphp
                                        
                                        <div class="preview-element preview-seat {{ $type }}"
                                             style="left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px;"
                                             title="{{ ucfirst($type) }} - {{ $number }}">
                                            {{ is_numeric($number) ? $number : substr($number, -2) }}
                                        </div>
                                    @endforeach
                                    
                                    @if(empty($customSeats))
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                            Belum ada kursi
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Legend -->
                            <div class="flex justify-center space-x-4 mt-3 text-xs">
                                @if($sellingMode === 'per_table')
                                    @php
                                        $shapeCount = [];
                                        foreach($config['tables'] ?? [] as $table) {
                                            $shape = $table['shape'] ?? 'square';
                                            $shapeCount[$shape] = ($shapeCount[$shape] ?? 0) + 1;
                                        }
                                    @endphp
                                    
                                    @foreach($shapeCount as $shape => $count)
                                        <div class="flex items-center space-x-1">
                                            <div class="w-3 h-3 bg-purple-500 
                                                {{ $shape === 'circle' ? 'rounded-full' : ($shape === 'diamond' ? 'transform rotate-45' : 'rounded-sm') }}">
                                            </div>
                                            <span class="text-gray-600">{{ ucfirst($shape) }} ({{ $count }})</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-blue-400 rounded-sm"></div>
                                        <span class="text-gray-600">Regular ({{ $regularCount }})</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-yellow-400 rounded-sm"></div>
                                        <span class="text-gray-600">VIP ({{ $vipCount }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Layout Info -->
                        <div class="space-y-2 text-sm">
                            @if($sellingMode === 'per_table')
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Meja:</span>
                                    <span class="font-medium">{{ count($config['tables'] ?? []) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Kapasitas:</span>
                                    <span class="font-medium">
                                        {{ collect($config['tables'] ?? [])->sum('capacity') }} orang
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga per Meja:</span>
                                    <span class="font-medium">Rp {{ number_format($config['table_price'] ?? 500000) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Est. Revenue:</span>
                                    <span class="font-medium text-green-600">
                                        Rp {{ number_format((count($config['tables'] ?? []) * ($config['table_price'] ?? 500000))) }}
                                    </span>
                                </div>
                            @else
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Kursi:</span>
                                    <span class="font-medium">{{ count($config['custom_seats'] ?? []) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga Regular:</span>
                                    <span class="font-medium">Rp {{ number_format($config['regular_price'] ?? 150000) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga VIP:</span>
                                    <span class="font-medium">Rp {{ number_format($config['vip_price'] ?? 300000) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Est. Revenue:</span>
                                    <span class="font-medium text-green-600">
                                        Rp {{ number_format(($regularCount * ($config['regular_price'] ?? 150000)) + ($vipCount * ($config['vip_price'] ?? 300000))) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-500">Dibuat:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($layout['created_at'])->format('d M Y') }}</span>
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

        <!-- Create/Edit Layout Modal -->
        @if($showLayoutModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="layout-modal">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                    <div class="relative bg-white rounded-lg shadow-xl transform transition-all w-full max-w-7xl max-h-[90vh] overflow-hidden">
                        <form wire:submit.prevent="saveLayout" class="h-full flex flex-col">
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
                                            <input wire:model.live="layout_name" 
                                                   type="text" 
                                                   id="layout_name"
                                                   placeholder="contoh: Main Hall Layout"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('layout_name') border-red-300 @enderror">
                                            @error('layout_name') 
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                            @enderror
                                        </div>
                                        
                                        <!-- Mode Penjualan -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Mode Penjualan</h4>
                                            <div class="mode-toggle" id="mode-toggle">
                                                <button type="button" 
                                                        data-mode="per_seat"
                                                        class="{{ $selling_mode === 'per_seat' ? 'active' : '' }}">
                                                    Per Kursi
                                                </button>
                                                <button type="button" 
                                                        data-mode="per_table"
                                                        class="{{ $selling_mode === 'per_table' ? 'active' : '' }}">
                                                    Per Meja
                                                </button>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500">
                                                <span id="mode-description">
                                                    @if($selling_mode === 'per_table')
                                                        Pelanggan membeli seluruh meja dengan semua kursinya
                                                    @else
                                                        Pelanggan membeli kursi individual
                                                    @endif
                                                </span>
                                            </p>
                                        </div>

                                        <!-- Table Shape Selector (only for per_table mode) -->
                                        <div id="table-shape-section" style="display: {{ $selling_mode === 'per_table' ? 'block' : 'none' }};">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Bentuk Meja</h4>
                                            <div class="table-shape-selector">
                                                <button type="button" 
                                                        class="table-shape-btn active" 
                                                        data-shape="square"
                                                        onclick="SeatLayoutManager.setTableShape('square')">
                                                    <div class="table-shape-preview square"></div>
                                                    <span class="text-xs font-medium">Square</span>
                                                </button>
                                                <button type="button" 
                                                        class="table-shape-btn" 
                                                        data-shape="circle"
                                                        onclick="SeatLayoutManager.setTableShape('circle')">
                                                    <div class="table-shape-preview circle"></div>
                                                    <span class="text-xs font-medium">Circle</span>
                                                </button>
                                                <button type="button" 
                                                        class="table-shape-btn" 
                                                        data-shape="rectangle"
                                                        onclick="SeatLayoutManager.setTableShape('rectangle')">
                                                    <div class="table-shape-preview rectangle"></div>
                                                    <span class="text-xs font-medium">Rectangle</span>
                                                </button>
                                                <button type="button" 
                                                        class="table-shape-btn" 
                                                        data-shape="diamond"
                                                        onclick="SeatLayoutManager.setTableShape('diamond')">
                                                    <div class="table-shape-preview diamond"></div>
                                                    <span class="text-xs font-medium">Diamond</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Tools -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Tools</h4>
                                            <div class="space-y-2" id="tools-container">
                                                <!-- Tools will be dynamically updated based on mode -->
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h4>
                                            <div class="space-y-2" id="quick-actions">
                                                <!-- Quick actions will be dynamically updated -->
                                            </div>
                                        </div>

                                        <!-- Background Image -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Background Image</h4>
                                            <div class="space-y-2">
                                                <input type="file" 
                                                       id="background_image" 
                                                       accept="image/*"
                                                       onchange="SeatLayoutManager.handleBackgroundUpload(this)"
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                <div class="flex space-x-2">
                                                    <button type="button" 
                                                            onclick="SeatLayoutManager.removeBackground()"
                                                            class="flex-1 px-2 py-1 border border-red-300 rounded text-xs text-red-600 hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                    <button type="button" 
                                                            onclick="SeatLayoutManager.toggleBackgroundOpacity()"
                                                            class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs hover:bg-gray-50">
                                                        Opacity
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pricing -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Harga</h4>
                                            <div class="space-y-3" id="pricing-section">
                                                <!-- Pricing inputs will be dynamically updated -->
                                            </div>
                                        </div>

                                        <!-- Statistics -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik</h4>
                                            <div class="space-y-2 text-sm" id="statistics">
                                                <!-- Statistics will be dynamically updated -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Layout Canvas -->
                                <div class="flex-1 flex flex-col overflow-auto">
                                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                                        <button type="button" 
                                                wire:click="closeModal"
                                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            Batal
                                        </button>
                                        <button type="submit" 
                                                onclick="saveLayout()"
                                                id="save-button"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                                                wire:loading.attr="disabled">
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
                                    <div class="p-6 flex-1">
                                        <div class="mb-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Layout Designer</h4>
                                            <p class="text-xs text-gray-500 mb-4" id="canvas-instruction">
                                                Pilih tool di sidebar, lalu klik di canvas untuk menambah elemen. Drag untuk memindahkan, resize dengan handle di pojok.
                                            </p>
                                        </div>
                                        
                                        <!-- Stage -->
                                        <div class="mb-6">
                                            <div class="bg-gray-800 text-white text-center py-4 px-6 rounded-lg text-sm font-medium max-w-md mx-auto">
                                                🎭 PANGGUNG
                                            </div>
                                        </div>

                                        <!-- Canvas Area -->
                                        <div class="relative bg-white border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner"
                                             id="seat-canvas"
                                             style="height: 500px; width: 100%; min-height: 500px;">
                                            
                                            <!-- Zoom Controls -->
                                            <div class="zoom-controls">
                                                <button class="zoom-btn" onclick="SeatLayoutManager.zoomOut()" title="Zoom Out">-</button>
                                                <div class="zoom-level" id="zoom-level">100%</div>
                                                <button class="zoom-btn" onclick="SeatLayoutManager.zoomIn()" title="Zoom In">+</button>
                                                <button class="zoom-btn" onclick="SeatLayoutManager.resetZoom()" title="Reset Zoom">⌂</button>
                                            </div>
                                            
                                            <!-- Background Image Container -->
                                            <div id="background-image-container" class="absolute inset-0 z-0 pointer-events-none">
                                                <!-- Background image will be inserted here -->
                                            </div>
                                            
                                            <!-- Zoom Container -->
                                            <div id="zoom-container" class="zoom-container relative h-full w-full z-10">
                                                <!-- Elements will be dynamically added here -->
                                                <div id="elements-container" class="relative h-full w-full"></div>
                                            </div>
                                        </div>

                                        <!-- Instructions -->
                                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                            <div class="text-xs text-gray-600">
                                                <h5 class="font-semibold text-gray-900 mb-2">Instruksi Penggunaan:</h5>
                                                <div class="space-y-2">
                                                    <div><strong>1. Pilih Mode:</strong> Per Kursi (individual) atau Per Meja (langsung booking meja)</div>
                                                    <div><strong>2. Bentuk Meja:</strong> Pilih bentuk meja sebelum menambahkan (hanya mode Per Meja)</div>
                                                    <div><strong>3. Tambah Elemen:</strong> Klik di canvas untuk menambah kursi/meja</div>
                                                    <div><strong>4. Pindahkan:</strong> Drag elemen untuk memindahkan posisi</div>
                                                    <div><strong>5. Resize:</strong> Pilih elemen, lalu drag handle di pojok untuk mengubah ukuran</div>
                                                    <div><strong>6. Edit:</strong> Klik kursi untuk toggle Regular/VIP (hanya mode Per Kursi)</div>
                                                </div>
                                                <div class="mt-3">
                                                    <h5 class="font-semibold text-gray-900 mb-2">Perbedaan Mode:</h5>
                                                    <div class="space-y-1">
                                                        <div><strong>Per Kursi:</strong> Customer beli kursi individual, bisa Regular/VIP</div>
                                                        <div><strong>Per Meja:</strong> Customer beli langsung per meja, tidak ada kursi individual</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h5 class="font-semibold text-gray-900 mb-2">Keyboard Shortcuts:</h5>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div><strong>Delete:</strong> Hapus selected</div>
                                                        <div><strong>Escape:</strong> Clear selection</div>
                                                        <div><strong>Ctrl+A:</strong> Select all</div>
                                                        <div><strong>R:</strong> Resize mode</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 p-2 bg-blue-50 rounded text-blue-800">
                                                    <strong>💡 Mode Per Meja:</strong> Tidak ada kursi individual. Customer langsung memesan meja dengan kapasitas tertentu.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<script>
// Enhanced Seat Layout Manager with Resizable Elements and Edit Functionality - FIXED VERSION
window.SeatLayoutManager = {
    // Core properties
    currentTool: 'select',
    sellingMode: 'per_seat', // Default fallback
    seats: [],
    tables: [],
    
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
    
    // Initialization state
    initialized: false,
    initRetryCount: 0,
    maxInitRetries: 15,
    
    init() {
        console.log('🚀 Initializing Enhanced Seat Layout Manager with Edit Support');
        console.log('Initial data - Seats:', this.seats.length, 'Tables:', this.tables.length);
        this.initialized = false;
        this.initRetryCount = 0;

        // Load initial data first
        this.loadInitialData();
        
        // Check DOM and proceed
        this.checkDOMReady();
        
        console.log('✅ Enhanced initialization started');
    },

loadInitialData() {
        try {
            console.log('📡 Loading initial data...');
            
            // Safely get data from Livewire if available
            if (window.Livewire && window.Livewire.all().length > 0) {
                const component = window.Livewire.all()[0];
                if (component.get) {
                    this.sellingMode = component.get('selling_mode') || 'per_seat';
                    
                    // FIXED: Load both seats and tables data properly
                    const livewireSeats = component.get('custom_seats') || [];
                    const livewireTables = component.get('tables') || [];
                    
                    // Transform and validate data
                    this.seats = this.transformSeatsData(livewireSeats);
                    this.tables = this.transformTablesData(livewireTables);
                    
                    console.log('📊 Data loaded from Livewire:', {
                        mode: this.sellingMode,
                        seats: this.seats.length,
                        tables: this.tables.length,
                        seats_sample: this.seats.slice(0, 2),
                        tables_sample: this.tables.slice(0, 2)
                    });
                }
            } else {
                console.log('⚠️ Livewire not available - using defaults');
                this.sellingMode = 'per_seat';
                this.seats = [];
                this.tables = [];
            }
        } catch (error) {
            console.error('❌ Error loading initial data:', error);
            // Use fallback values
            this.sellingMode = 'per_seat';
            this.seats = [];
            this.tables = [];
        }
    },

    transformSeatsData(rawSeats) {
        if (!Array.isArray(rawSeats)) {
            console.warn('⚠️ Invalid seats data - not an array:', rawSeats);
            return [];
        }

        return rawSeats.map((seat, index) => {
            const transformed = {
                id: seat.id || `seat_${index + 1}`,
                x: parseInt(seat.x) || 0,
                y: parseInt(seat.y) || 0,
                type: seat.type || 'Regular',
                row: seat.row || this.generateSeatRow(seat.y || 0),
                number: seat.number || (index + 1),
                width: parseInt(seat.width) || 44,
                height: parseInt(seat.height) || 44
            };
            
            console.log(`🪑 Transformed seat ${index}:`, { original: seat, transformed });
            return transformed;
        });
    },

    transformTablesData(rawTables) {
        if (!Array.isArray(rawTables)) {
            console.warn('⚠️ Invalid tables data - not an array:', rawTables);
            return [];
        }

        return rawTables.map((table, index) => {
            const transformed = {
                id: table.id || `table_${index + 1}`,
                x: parseInt(table.x) || 0,
                y: parseInt(table.y) || 0,
                shape: table.shape || 'square',
                capacity: parseInt(table.capacity) || 4,
                number: table.number || `T${index + 1}`,
                width: parseInt(table.width) || 120,
                height: parseInt(table.height) || 120
            };
            
            console.log(`🍽️ Transformed table ${index}:`, { original: table, transformed });
            return transformed;
        });
    },

    checkDOMReady() {
        const canvas = document.getElementById('seat-canvas');
        const container = document.getElementById('elements-container');
        const modal = document.querySelector('[wire\\:key="layout-modal"]');
        
        console.log(`🔍 DOM Check (attempt ${this.initRetryCount + 1}/${this.maxInitRetries}):`, {
            canvas: !!canvas,
            container: !!container,
            modal: !!modal,
            modalVisible: modal ? window.getComputedStyle(modal).display !== 'none' : false
        });
        
        if (canvas && container && modal && window.getComputedStyle(modal).display !== 'none') {
            console.log('✅ DOM is ready, proceeding with initialization...');
            this.proceedWithInit();
        } else {
            this.initRetryCount++;
            if (this.initRetryCount < this.maxInitRetries) {
                console.log(`⏳ DOM not ready, retrying in 200ms... (${this.initRetryCount}/${this.maxInitRetries})`);
                setTimeout(() => this.checkDOMReady(), 200);
            } else {
                console.error('❌ DOM not ready after max retries. Manual initialization required.');
                this.showDOMErrorMessage();
            }
        }
    },

    proceedWithInit() {
        try {
            console.log('🎯 Proceeding with full initialization...');
            
            this.initializeCounters();
            this.setupCanvas();
            this.setupModeToggle();
            this.updateInterface();
            
            // FIXED: Always render existing data after interface is ready
            this.renderAll();
            
            this.setupKeyboardShortcuts();
            this.updateStatistics();
            
            // Set default tool
            this.setTool('select');
            
            this.initialized = true;
            console.log('✅ Enhanced initialization completed successfully!');
            
            // Remove any error messages
            const errorMsg = document.querySelector('div[style*="background: #fee"]');
            if (errorMsg) errorMsg.remove();
            
        } catch (error) {
            console.error('❌ Error during initialization:', error);
            this.initialized = false;
        }
    },

    initializeCounters() {
        try {
            // Initialize seat counter
            if (this.seats.length > 0) {
                const seatNumbers = this.seats.map(s => {
                    const idMatch = s.id.match(/\d+/);
                    return idMatch ? parseInt(idMatch[0]) : 0;
                });
                this.seatCounter = Math.max(...seatNumbers, 0);
            } else {
                this.seatCounter = 0;
            }

            // Initialize table counter
            if (this.tables.length > 0) {
                const tableNumbers = this.tables.map(t => {
                    const idMatch = t.id.match(/\d+/);
                    return idMatch ? parseInt(idMatch[0]) : 0;
                });
                this.tableCounter = Math.max(...tableNumbers, 0);
            } else {
                this.tableCounter = 0;
            }
            
            console.log('🔢 Counters initialized:', { 
                seats: this.seatCounter, 
                tables: this.tableCounter,
                existing_seats: this.seats.length,
                existing_tables: this.tables.length
            });
        } catch (error) {
            console.warn('⚠️ Error initializing counters:', error);
            this.seatCounter = 0;
            this.tableCounter = 0;
        }
    },

    // showDOMErrorMessage() {
    //     console.error('❌ INITIALIZATION FAILED: DOM elements not found');
    //     console.log('🔧 Try running: window.SeatLayoutManager.forceInit()');
        
    //     setTimeout(() => {
    //         const errorDiv = document.createElement('div');
    //         errorDiv.innerHTML = `
    //             <div style="background: #fee; border: 1px solid #fcc; padding: 12px; margin: 12px; border-radius: 4px; font-size: 14px;">
    //                 <strong>⚠️ Layout Designer Error:</strong><br>
    //                 Canvas tidak dapat dimuat. <button onclick="window.SeatLayoutManager.forceInit()" style="background: #007cba; color: white; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer;">Coba Lagi</button>
    //             </div>
    //         `;
            
    //         const targetContainer = document.querySelector('.modal-content') || document.querySelector('.bg-white.rounded-lg') || document.body;
    //         if (targetContainer) {
    //             targetContainer.prepend(errorDiv.firstElementChild);
    //         }
    //     }, 100);
    // },

    forceInit() {
        console.log('🔧 Force initialization triggered...');
        this.initRetryCount = 0;
        this.checkDOMReady();
    },

    setupCanvas() {
        const canvas = document.getElementById('seat-canvas');
        const container = document.getElementById('elements-container');
        
        if (!canvas || !container) {
            throw new Error('Canvas or elements container not found during setupCanvas');
        }

        console.log('🎨 Setting up canvas interactions...');

        // Remove existing event listeners to prevent duplicates
        if (this.canvasClickHandler) {
            canvas.removeEventListener('click', this.canvasClickHandler);
        }
        
        // Create bound handler
        this.canvasClickHandler = (event) => {
            console.log('🖱️ Canvas clicked:', {
                target: event.target.tagName,
                className: event.target.className,
                tool: this.currentTool
            });
            
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
            
            console.log('📍 Click position:', { x, y, tool: this.currentTool });
            
            const snappedX = this.snapToGrid ? Math.round(x / this.gridSize) * this.gridSize : x;
            const snappedY = this.snapToGrid ? Math.round(y / this.gridSize) * this.gridSize : y;
            
            this.handleCanvasClick(snappedX, snappedY);
        };
        
        // Add the event listener
        canvas.addEventListener('click', this.canvasClickHandler);
        this.updateCanvasCursor();
        
        console.log('✅ Canvas setup completed successfully');
    },

    setupModeToggle() {
        const modeToggle = document.getElementById('mode-toggle');
        if (!modeToggle) {
            console.warn('⚠️ Mode toggle not found');
            return;
        }

        // Remove existing listeners
        const newToggle = modeToggle.cloneNode(true);
        modeToggle.parentNode.replaceChild(newToggle, modeToggle);

        newToggle.addEventListener('click', (e) => {
            if (e.target.dataset.mode) {
                this.setSellingMode(e.target.dataset.mode);
            }
        });
        
        console.log('✅ Mode toggle setup completed');
    },

    setSellingMode(mode) {
        console.log('🔄 Changing selling mode to:', mode);
        this.sellingMode = mode;
        
        // Update Livewire safely
        try {
            if (window.Livewire && window.Livewire.all().length > 0) {
                window.Livewire.all()[0].set('selling_mode', mode);
            }
        } catch (error) {
            console.warn('⚠️ Could not update Livewire selling mode:', error);
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
        
        // Clear all data when switching modes
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
        console.log('🎯 Handling canvas click:', { x, y, tool: this.currentTool });
        
        switch (this.currentTool) {
            case 'regular':
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('Regular', x, y);
                }
                break;
            case 'vip':
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('VIP', x, y);
                }
                break;
            case 'table':
                if (this.sellingMode === 'per_table') {
                    this.createTable(x, y, this.currentTableShape);
                }
                break;
        }
    },

    createSeat(type, x, y, tableId = null, width = 44, height = 44) {
        this.seatCounter++;
        console.log('🪑 Creating seat:', { type, x, y, counter: this.seatCounter });
        
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
        this.renderSeat(seat);
        this.updateStatistics();
        
        console.log('✅ Seat created successfully');
    },

    createTable(x, y, shape = 'square') {
        this.tableCounter++;
        console.log('🍽️ Creating table:', { x, y, shape, counter: this.tableCounter });
        
        const capacity = parseInt(document.getElementById('table_capacity')?.value || '4');
        
        let width = 120, height = 120;
        switch (shape) {
            case 'rectangle':
                width = 160; height = 100; break;
            case 'circle':
                width = 120; height = 120; break;
            case 'diamond':
                width = 120; height = 120; break;
            default:
                width = 120; height = 120;
        }
        
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
        
        this.tables.push(table);
        this.renderTable(table);
        this.updateStatistics();
        
        console.log('✅ Table created successfully');
    },

    renderAll() {
        if (!this.initialized && !document.getElementById('seat-canvas')) {
            console.warn('⚠️ Cannot render - canvas not available');
            return;
        }
        
        console.log('🎨 Rendering all elements:', {
            seats: this.seats.length,
            tables: this.tables.length,
            mode: this.sellingMode
        });
        
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Elements container not found for renderAll!');
            return;
        }
        
        // Clear existing elements
        container.innerHTML = '';
        console.log('🧹 Cleared existing elements');
        
        // Render tables first (lower z-index)
        if (this.tables && this.tables.length > 0) {
            this.tables.forEach((table, index) => {
                try {
                    console.log(`🍽️ Rendering table ${index + 1}/${this.tables.length}:`, table);
                    this.renderTable(table);
                } catch (error) {
                    console.error(`❌ Error rendering table ${table.id}:`, error);
                }
            });
        }
        
        // Render seats
        if (this.seats && this.seats.length > 0) {
            this.seats.forEach((seat, index) => {
                try {
                    console.log(`🪑 Rendering seat ${index + 1}/${this.seats.length}:`, seat);
                    this.renderSeat(seat);
                } catch (error) {
                    console.error(`❌ Error rendering seat ${seat.id}:`, error);
                }
            });
        }
        
        console.log('✅ All elements rendered successfully');
    },

     renderTable(table) {
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Cannot render table - container not found');
            return;
        }

        const element = document.createElement('div');
        
        element.className = `table-element shape-${table.shape || 'square'}`;
        element.style.left = (table.x || 0) + 'px';
        element.style.top = (table.y || 0) + 'px';
        element.style.width = (table.width || 120) + 'px';
        element.style.height = (table.height || 120) + 'px';
        element.dataset.tableId = table.id;
        
        // Apply rotation for diamond shape
        if (table.shape === 'diamond') {
            element.style.transform = 'rotate(45deg)';
        }
        
        element.innerHTML = `
            <div class="table-content">
                <div class="table-number">${table.number || 'T1'}</div>
                <div class="table-capacity">${table.capacity || 4} kursi</div>
            </div>
            ${this.createResizeHandles()}
        `;
        
        this.makeTableInteractive(element);
        container.appendChild(element);
        
        console.log('✅ Table rendered:', table.id);
    },

    renderSeat(seat) {
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Cannot render seat - container not found');
            return;
        }

        const element = document.createElement('div');
        const isTableSeat = seat.table_id !== null && seat.table_id !== undefined;
        
        element.className = `seat-element ${(seat.type || 'regular').toLowerCase()} ${isTableSeat ? 'table-seat' : ''}`;
        element.style.left = (seat.x || 0) + 'px';
        element.style.top = (seat.y || 0) + 'px';
        element.style.width = (seat.width || 44) + 'px';
        element.style.height = (seat.height || 44) + 'px';
        element.dataset.seatId = seat.id;
        
        element.innerHTML = `
            <span>${seat.number || 1}</span>
            ${this.createResizeHandles()}
        `;
        
        this.makeSeatInteractive(element);
        container.appendChild(element);
        
        console.log('✅ Seat rendered:', seat.id);
    },

    reloadDataFromLivewire() {
        console.log('🔄 Reloading data from Livewire...');
        
        try {
            this.loadInitialData();
            
            // Update interface to match loaded data
            this.updateInterface();
            
            // Re-render everything
            this.renderAll();
            
            // Update statistics
            this.updateStatistics();
            
            console.log('✅ Data reloaded successfully:', {
                mode: this.sellingMode,
                seats: this.seats.length,
                tables: this.tables.length
            });
            
            return true;
        } catch (error) {
            console.error('❌ Error reloading data:', error);
            return false;
        }
    },

    forceInitForEdit() {
        console.log('🔧 Force initialization for edit mode...');
        
        // Reset initialization state
        this.initialized = false;
        this.initRetryCount = 0;
        
        // Load fresh data from Livewire
        this.loadInitialData();
        
        // Proceed with initialization
        this.checkDOMReady();
        
        console.log('✅ Edit mode initialization triggered');
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

    // Continue with remaining methods...
    makeTableInteractive(element) {
        const tableId = element.dataset.tableId;
        const table = this.tables.find(t => t.id === tableId);
        
        // Make draggable and interactive
        element.addEventListener('click', (event) => {
            if (!event.target.closest('.resize-handle')) {
                this.handleElementClick(element, 'table');
            }
        });
        
        // Add dragging functionality
        this.makeDraggable(element, 'table');
        
        // Add resize functionality
        this.makeResizable(element, 'table');
    },

    makeSeatInteractive(element) {
        const seatId = element.dataset.seatId;
        const seat = this.seats.find(s => s.id === seatId);
        
        // Make draggable and interactive
        element.addEventListener('click', (event) => {
            if (!event.target.closest('.resize-handle')) {
                this.handleElementClick(element, 'seat');
            }
        });
        
        // Add dragging functionality
        this.makeDraggable(element, 'seat');
        
        // Add resize functionality
        this.makeResizable(element, 'seat');
    },

    makeResizable(element, type) {
        const resizeHandles = element.querySelectorAll('.resize-handle');
        
        resizeHandles.forEach(handle => {
            handle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                e.preventDefault();
                
                const direction = handle.dataset.direction;
                const startX = e.clientX;
                const startY = e.clientY;
                const startWidth = parseInt(element.style.width);
                const startHeight = parseInt(element.style.height);
                const startLeft = parseInt(element.style.left);
                const startTop = parseInt(element.style.top);
                
                console.log('🔧 Starting resize:', { direction, type });
                
                const handleMouseMove = (e) => {
                    const deltaX = e.clientX - startX;
                    const deltaY = e.clientY - startY;
                    
                    let newWidth = startWidth;
                    let newHeight = startHeight;
                    let newLeft = startLeft;
                    let newTop = startTop;
                    
                    // Calculate new dimensions based on direction
                    switch (direction) {
                        case 'se': // Southeast
                            newWidth = startWidth + deltaX;
                            newHeight = startHeight + deltaY;
                            break;
                        case 'sw': // Southwest
                            newWidth = startWidth - deltaX;
                            newHeight = startHeight + deltaY;
                            newLeft = startLeft + deltaX;
                            break;
                        case 'ne': // Northeast
                            newWidth = startWidth + deltaX;
                            newHeight = startHeight - deltaY;
                            newTop = startTop + deltaY;
                            break;
                        case 'nw': // Northwest
                            newWidth = startWidth - deltaX;
                            newHeight = startHeight - deltaY;
                            newLeft = startLeft + deltaX;
                            newTop = startTop + deltaY;
                            break;
                        case 'n': // North
                            newHeight = startHeight - deltaY;
                            newTop = startTop + deltaY;
                            break;
                        case 's': // South
                            newHeight = startHeight + deltaY;
                            break;
                        case 'w': // West
                            newWidth = startWidth - deltaX;
                            newLeft = startLeft + deltaX;
                            break;
                        case 'e': // East
                            newWidth = startWidth + deltaX;
                            break;
                    }
                    
                    // Apply size constraints
                    if (type === 'seat') {
                        // For seats, keep them square
                        const size = Math.max(this.minSeatSize, Math.min(this.maxSeatSize, Math.min(newWidth, newHeight)));
                        newWidth = size;
                        newHeight = size;
                    } else {
                        // For tables, apply min/max constraints
                        newWidth = Math.max(this.minTableSize, Math.min(this.maxTableSize, newWidth));
                        newHeight = Math.max(this.minTableSize, Math.min(this.maxTableSize, newHeight));
                    }
                    
                    // Apply grid snapping if enabled
                    if (this.snapToGrid) {
                        newLeft = Math.round(newLeft / this.gridSize) * this.gridSize;
                        newTop = Math.round(newTop / this.gridSize) * this.gridSize;
                        newWidth = Math.round(newWidth / this.gridSize) * this.gridSize;
                        newHeight = Math.round(newHeight / this.gridSize) * this.gridSize;
                    }
                    
                    // Update element styles
                    element.style.width = newWidth + 'px';
                    element.style.height = newHeight + 'px';
                    element.style.left = newLeft + 'px';
                    element.style.top = newTop + 'px';
                    
                    // Update data
                    if (type === 'table') {
                        const table = this.tables.find(t => t.id === element.dataset.tableId);
                        if (table) {
                            table.width = newWidth;
                            table.height = newHeight;
                            table.x = newLeft;
                            table.y = newTop;
                        }
                    } else {
                        const seat = this.seats.find(s => s.id === element.dataset.seatId);
                        if (seat) {
                            seat.width = newWidth;
                            seat.height = newHeight;
                            seat.x = newLeft;
                            seat.y = newTop;
                        }
                    }
                };
                
                const handleMouseUp = () => {
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                    document.body.style.cursor = 'default';
                    console.log('✅ Resize completed');
                };
                
                // Set cursor for resize direction
                const cursorMap = {
                    'nw': 'nw-resize',
                    'ne': 'ne-resize',
                    'sw': 'sw-resize',
                    'se': 'se-resize',
                    'n': 'n-resize',
                    's': 's-resize',
                    'w': 'w-resize',
                    'e': 'e-resize'
                };
                document.body.style.cursor = cursorMap[direction] || 'default';
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            });
        });
    },

    makeDraggable(element, type) {
        let isDragging = false;
        let startX, startY, initialX, initialY;
        
        element.addEventListener('mousedown', (e) => {
            if (e.target.classList.contains('resize-handle')) return;
            
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            initialX = parseInt(element.style.left) || 0;
            initialY = parseInt(element.style.top) || 0;
            
            element.classList.add('dragging');
            
            const handleMouseMove = (e) => {
                if (!isDragging) return;
                
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                
                const newX = initialX + deltaX;
                const newY = initialY + deltaY;
                
                const snappedX = this.snapToGrid ? Math.round(newX / this.gridSize) * this.gridSize : newX;
                const snappedY = this.snapToGrid ? Math.round(newY / this.gridSize) * this.gridSize : newY;
                
                element.style.left = snappedX + 'px';
                element.style.top = snappedY + 'px';
                
                // Update data
                if (type === 'table') {
                    this.updateTablePosition(element.dataset.tableId, snappedX, snappedY);
                } else {
                    this.updateSeatPosition(element.dataset.seatId, snappedX, snappedY);
                }
            };
            
            const handleMouseUp = () => {
                isDragging = false;
                element.classList.remove('dragging');
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            };
            
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
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
            console.log('Table position updated:', tableId, 'to', x, y);
        }
    },

    updateSeatPosition(seatId, x, y) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat) {
            seat.x = x;
            seat.y = y;
            console.log('Seat position updated:', seatId, 'to', x, y);
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
        
        console.log('Table deleted:', tableId);
        this.updateStatistics();
    },

    deleteSeat(seatId) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat && seat.table_id) {
            // Remove from table seats
            const table = this.tables.find(t => t.id === seat.table_id);
            if (table && table.seats) {
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
            let totalCapacity = 0;
            this.tables.forEach(table => {
                totalCapacity += table.capacity || 4;
            });

            estimatedRevenue = totalTables * tablePrice;
            
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
        
        // Basic validation
        const layoutName = document.getElementById('layout_name')?.value || '';
        if (!layoutName.trim()) {
            errors.push('Nama layout harus diisi');
        }
        
        if (this.sellingMode === 'per_table') {
            if (this.tables.length === 0) {
                errors.push('Layout harus memiliki minimal satu meja');
                console.error('❌ No tables found in per_table mode');
            }
            
            // Validate each table
            this.tables.forEach((table, index) => {
                if (!table.x && table.x !== 0 || !table.y && table.y !== 0) {
                    errors.push(`Meja ${index + 1} tidak memiliki posisi yang valid`);
                }
            });
            
            console.log('📊 Table validation:', {
                tables_count: this.tables.length,
                tables_data: this.tables.slice(0, 2) // Log the first 2 tables
            });
        } else {
            if (this.seats.length === 0) {
                errors.push('Layout harus memiliki minimal satu kursi');
                console.error('❌ No seats found in per_seat mode');
            }
            
            // Validate each seat
            this.seats.forEach((seat, index) => {
                if (!seat.x && seat.x !== 0 || !seat.y && seat.y !== 0) {
                    errors.push(`Kursi ${index + 1} tidak memiliki posisi yang valid`);
                }
                if (!seat.type || !['Regular', 'VIP'].includes(seat.type)) {
                    errors.push(`Kursi ${index + 1} tipe tidak valid`);
                }
            });
            
            console.log('📊 Seat validation:', {
                seats_count: this.seats.length,
                seats_data: this.seats.slice(0, 2) // Log the first 2 seats
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

    // Enhanced sync with validation - FIXED LIVEWIRE SYNC
    syncWithLivewire() {
        console.log('📡 Syncing data with Livewire...');
        
        // Check if canvas is available
        const canvas = document.getElementById('seat-canvas');
        if (!canvas) {
            console.error('❌ Cannot sync - canvas not found');
            alert('Error: Canvas tidak tersedia. Mohon refresh halaman dan coba lagi.');
            return false;
        }
        
        // Validate first
        if (!this.validateLayout()) {
            console.error('❌ Cannot sync - validation failed');
            return false;
        }
        
        // Log data mode penjualan dan jumlah data yang akan disinkronkan
        console.log(`🔍 Selling mode: ${this.sellingMode}`);
        console.log(`🔢 Data count: ${this.sellingMode === 'per_seat' ? this.seats.length + ' seats' : this.tables.length + ' tables'}`);
        
        // Transform data based on selling mode
        let dataToSync;
        
        if (this.sellingMode === 'per_table') {
            // Transform tables data for backend
            const transformedTables = this.tables.map(table => {
                return {
                    id: table.id,
                    x: table.x,
                    y: table.y,
                    shape: table.shape || 'square',
                    capacity: table.capacity || 4,
                    number: table.number || ('T' + table.id.replace('table_', '')),
                    width: table.width || 120,
                    height: table.height || 120
                };
            });
            
            dataToSync = {
                selling_mode: 'per_table',
                tables: transformedTables,
                custom_seats: [] // Kosongkan data kursi
            };
            
            console.log('📊 Tables data to sync:', {
                tables_count: transformedTables.length,
                sample_table: transformedTables.length > 0 ? transformedTables[0] : null
            });
        } else {
            // Transform seats data for backend
            const transformedSeats = this.seats.map(seat => {
                return {
                    id: seat.id,
                    x: seat.x,
                    y: seat.y,
                    type: seat.type,
                    row: seat.row || this.generateSeatRow(seat.y),
                    number: seat.number || seat.id.replace('seat_', ''),
                    width: seat.width || 44,
                    height: seat.height || 44
                };
            });
            
            dataToSync = {
                selling_mode: 'per_seat',
                custom_seats: transformedSeats,
                tables: [] // Kosongkan data meja
            };
            
            console.log('📊 Seats data to sync:', {
                seats_count: transformedSeats.length,
                sample_seat: transformedSeats.length > 0 ? transformedSeats[0] : null
            });
        }
        
        // Try multiple ways to access Livewire component
        let component = null;
        
        // Try Livewire.all()
        if (window.Livewire && window.Livewire.all) {
            try {
                const components = window.Livewire.all();
                if (components.length > 0) {
                    component = components[0];
                    console.log('✅ Using Livewire.all()[0]');
                }
            } catch (e) {
                console.log('⚠️ Livewire.all() failed:', e);
            }
        }
        
        if (component) {
            try {
                // Try different methods to set data
                if (typeof component.set === 'function') {
                    // Method 1: Direct set
                    component.set('selling_mode', dataToSync.selling_mode);
                    
                    if (this.sellingMode === 'per_table') {
                        component.set('tables', dataToSync.tables);
                        component.set('custom_seats', []);
                    } else {
                        component.set('custom_seats', dataToSync.custom_seats);
                        component.set('tables', []);
                    }
                    
                    console.log('✅ Data synced via set() method');
                    
                    // Verify data was set properly
                    try {
                        const verifyMode = component.get('selling_mode');
                        const verifyTables = component.get('tables') || [];
                        const verifySeats = component.get('custom_seats') || [];
                        
                        console.log('🔍 Verification after sync:', {
                            selling_mode: verifyMode,
                            tables_count: verifyTables.length,
                            seats_count: verifySeats.length
                        });
                    } catch (e) {
                        console.warn('⚠️ Could not verify data:', e);
                    }
                } else {
                    // Fallback: Try direct wire property update
                    if (component.$wire) {
                        component.$wire.selling_mode = dataToSync.selling_mode;
                        
                        if (this.sellingMode === 'per_table') {
                            component.$wire.tables = dataToSync.tables;
                            component.$wire.custom_seats = [];
                        } else {
                            component.$wire.custom_seats = dataToSync.custom_seats;
                            component.$wire.tables = [];
                        }
                        
                        console.log('✅ Data synced via $wire properties');
                    } else {
                        throw new Error('Cannot find appropriate method to sync data');
                    }
                }
                
                return true;
                
            } catch (error) {
                console.error('❌ Error syncing data:', error);
                
                // Fallback: Try to update form inputs manually
                try {
                    this.updateFormInputsManually(dataToSync);
                    console.log('✅ Used manual form input fallback');
                    return true;
                } catch (fallbackError) {
                    console.error('❌ Manual fallback also failed:', fallbackError);
                    return false;
                }
            }
        } else {
            console.error('❌ No Livewire component found');
            
            // Try manual form input update as fallback
            try {
                this.updateFormInputsManually(dataToSync);
                console.log('✅ Used manual form input fallback (no component)');
                return true;
            } catch (error) {
                alert('Livewire tidak tersedia untuk sinkronisasi data');
                return false;
            }
        }
    },

    // Manual fallback for updating form inputs
    updateFormInputsManually(dataToSync) {
        console.log('🔧 Using manual form input update...');
        
        // Create hidden inputs to store data
        const form = document.querySelector('form') || document.body;
        
        // Remove existing hidden inputs
        form.querySelectorAll('input[name="layout_data_seats"], input[name="layout_data_tables"], input[name="layout_data_mode"]').forEach(input => {
            input.remove();
        });
        
        // Create new hidden inputs
        const seatsInput = document.createElement('input');
        seatsInput.type = 'hidden';
        seatsInput.name = 'layout_data_seats';
        seatsInput.value = JSON.stringify(dataToSync.custom_seats || []);
        form.appendChild(seatsInput);
        
        const tablesInput = document.createElement('input');
        tablesInput.type = 'hidden';
        tablesInput.name = 'layout_data_tables';
        tablesInput.value = JSON.stringify(dataToSync.tables || []);
        form.appendChild(tablesInput);
        
        const modeInput = document.createElement('input');
        modeInput.type = 'hidden';
        modeInput.name = 'layout_data_mode';
        modeInput.value = dataToSync.selling_mode;
        form.appendChild(modeInput);
        
        console.log('✅ Manual form inputs created:', {
            mode: dataToSync.selling_mode,
            seats_count: (dataToSync.custom_seats || []).length,
            tables_count: (dataToSync.tables || []).length
        });
    },
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM Content Loaded');
    
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
    
    // FIXED: Listen for layout data loaded event
    Livewire.on('layout-data-loaded', (data) => {
        console.log('📡 Layout data loaded event received:', data);
        
        if (window.SeatLayoutManager) {
            // Update SeatLayoutManager with new data
            SeatLayoutManager.sellingMode = data[0].selling_mode;
            SeatLayoutManager.seats = data[0].custom_seats || [];
            SeatLayoutManager.tables = data[0].tables || [];
            
            console.log('📊 Data updated in SeatLayoutManager:', {
                mode: SeatLayoutManager.sellingMode,
                seats: SeatLayoutManager.seats.length,
                tables: SeatLayoutManager.tables.length
            });
            
            // Wait for modal to be fully rendered, then initialize
            setTimeout(() => {
                if (document.getElementById('seat-canvas')) {
                    console.log('🔄 Initializing SeatLayoutManager for edit mode...');
                    SeatLayoutManager.forceInitForEdit();
                } else {
                    console.warn('⚠️ Canvas not found after data load event');
                }
            }, 500);
        }
    });
    
    Livewire.hook('morph.updated', () => {
        console.log('🔄 Livewire DOM updated - Re-initializing...');
        setTimeout(() => {
            if (window.SeatLayoutManager && document.getElementById('seat-canvas')) {
                // FIXED: Use enhanced initialization for edit mode
                SeatLayoutManager.forceInitForEdit();
            }
        }, 300);
    });
});

// Enhanced form submission handler with validation
document.addEventListener('submit', function(e) {
    const form = e.target.closest('form');
    console.log('📨 Form submission detected:', form);
    if (form && window.SeatLayoutManager) {
        console.log('📝 Form submission intercepted...');
        e.preventDefault();
        
        if (!SeatLayoutManager.initialized) {
            console.error('❌ SeatLayoutManager not initialized');
            alert('Error: Layout designer belum siap. Mohon tunggu sebentar dan coba lagi.');
            return false;
        }
        
        const canvas = document.getElementById('seat-canvas');
        if (!canvas) {
            console.error('❌ Canvas not found during form submission');
            alert('Error: Canvas tidak tersedia. Mohon refresh halaman dan coba lagi.');
            return false;
        }
        
        console.log('🔍 Pre-submission validation...');
        const syncSuccess = SeatLayoutManager.syncWithLivewire();
        
        if (syncSuccess) {
            console.log('✅ Validation passed, submitting form...');
            setTimeout(() => {
                console.log('📨 Submitting form now...');
                
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.click();
                } else {
                    if (window.Livewire && window.Livewire.all().length > 0) {
                        window.Livewire.all()[0].call('saveLayout');
                    } else {
                        form.submit();
                    }
                }
            }, 100);
        } else {
            console.error('❌ Form submission cancelled due to validation failure');
        }
        
        return false;
    }
});

// Function to save layout manually
function saveLayout() {
    console.log('💾 Manual save layout triggered...');
    
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        alert('Layout Manager tidak tersedia');
        return;
    }
    
    const syncSuccess = SeatLayoutManager.syncWithLivewire();
    
    if (syncSuccess) {
        console.log('✅ Data synced, proceeding with save...');
        
        if (window.Livewire && window.Livewire.all().length > 0) {
            try {
                window.Livewire.all()[0].call('saveLayout');
                console.log('📤 Livewire saveLayout called');
            } catch (error) {
                console.error('❌ Error calling Livewire saveLayout:', error);
                alert('Error: Tidak dapat menyimpan layout. Mohon coba lagi.');
            }
        } else {
            console.error('❌ Livewire not available for save');
            alert('Error: Livewire tidak tersedia. Mohon refresh halaman.');
        }
    } else {
        console.error('❌ Cannot save - sync failed');
        alert('Gagal menyinkronkan data. Mohon periksa data layout dan coba lagi.');
    }
}

// Debug helper functions
window.debugSeatManager = function() {
    console.log('🐛 DEBUG INFO:', {
        initialized: SeatLayoutManager.initialized,
        canvasExists: !!document.getElementById('seat-canvas'),
        containerExists: !!document.getElementById('elements-container'),
        modalVisible: !!document.querySelector('[wire\\:key="layout-modal"]'),
        livewireAvailable: !!(window.Livewire && window.Livewire.all().length > 0),
        currentData: {
            mode: SeatLayoutManager.sellingMode,
            seats: SeatLayoutManager.seats.length,
            tables: SeatLayoutManager.tables.length
        }
    });
};

// Force initialization
window.forceInit = function() {
    console.log('🔧 Force initialization triggered by user...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.forceInit();
    }
};

// Test functions
window.testCreateSeat = function() {
    console.log('🧪 Testing seat creation...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.createSeat('Regular', 100, 100);
        SeatLayoutManager.createSeat('VIP', 150, 100);
        console.log('✅ Test seats created');
    }
};

window.testCreateTable = function() {
    console.log('🧪 Testing table creation...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.createTable(200, 200, 'square');
        console.log('✅ Test table created');
    }
};

// Check system status
window.checkSystemStatus = function() {
    console.log('📊 SYSTEM STATUS:');
    console.log('================');
    console.log('🚀 SeatLayoutManager:', !!window.SeatLayoutManager);
    console.log('⚡ Livewire:', !!(window.Livewire && window.Livewire.all().length > 0));
    console.log('🖥️ Canvas DOM:', !!document.getElementById('seat-canvas'));
    console.log('📦 Container DOM:', !!document.getElementById('elements-container'));
    console.log('🔧 Initialized:', window.SeatLayoutManager?.initialized || false);
    console.log('📋 Current Mode:', window.SeatLayoutManager?.sellingMode || 'unknown');
    console.log('🪑 Seats Count:', window.SeatLayoutManager?.seats?.length || 0);
    console.log('🍽️ Tables Count:', window.SeatLayoutManager?.tables?.length || 0);
    
    if (window.Livewire && window.Livewire.all().length > 0) {
        try {
            const component = window.Livewire.all()[0];
            console.log('📡 Livewire Data:');
            console.log('  - selling_mode:', component.get('selling_mode'));
            console.log('  - custom_seats:', (component.get('custom_seats') || []).length);
            console.log('  - tables:', (component.get('tables') || []).length);
        } catch (e) {
            console.log('⚠️ Cannot read Livewire data:', e);
        }
    }
    
    return {
        seatLayoutManager: !!window.SeatLayoutManager,
        livewire: !!(window.Livewire && window.Livewire.all().length > 0),
        canvas: !!document.getElementById('seat-canvas'),
        container: !!document.getElementById('elements-container'),
        initialized: window.SeatLayoutManager?.initialized || false
    };
};

// Force sync data
window.forceSyncData = function() {
    console.log('🔄 Force sync data triggered...');
    
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        return false;
    }
    
    const result = SeatLayoutManager.syncWithLivewire();
    console.log('🔍 Sync result:', result);
    return result;
};

// Test save layout
window.testSaveLayout = function() {
    console.log('🧪 Testing save layout...');
    
    // Create some test data first
    if (window.SeatLayoutManager) {
        if (SeatLayoutManager.sellingMode === 'per_table') {
            SeatLayoutManager.createTable(100, 100, 'square');
            SeatLayoutManager.createTable(250, 150, 'circle');
            console.log('🍽️ Created test tables');
        } else {
            SeatLayoutManager.createSeat('Regular', 100, 100);
            SeatLayoutManager.createSeat('VIP', 150, 100);
            console.log('🪑 Created test seats');
        }
        
        // Now try to save
        saveLayout();
    }
};

// Manual save layout
window.manualSaveLayout = function() {
    console.log('💾 Manual save layout...');
    saveLayout();
};

// Check tables data
window.checkTablesData = function() {
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        return;
    }
    
    const tableData = {
        tables: SeatLayoutManager.tables,
        count: SeatLayoutManager.tables.length,
        selling_mode: SeatLayoutManager.sellingMode
    };
    
    console.log('🍽️ Current Tables Data:', tableData);
    
    if (window.Livewire && window.Livewire.all().length > 0) {
        const component = window.Livewire.all()[0];
        const livewireTables = component.get ? component.get('tables') : null;
        const livewireMode = component.get ? component.get('selling_mode') : null;
        
        console.log('⚡ Livewire Tables Data:', {
            tables: livewireTables,
            count: livewireTables ? livewireTables.length : 0,
            selling_mode: livewireMode
        });
    }
    
    return tableData;
};

// Force table mode
window.forceTableMode = function() {
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        return;
    }
    
    // Set to table mode
    SeatLayoutManager.setSellingMode('per_table');
    
    // Clear existing elements
    SeatLayoutManager.clearAll();
    
    // Create some tables
    SeatLayoutManager.createTable(100, 100, 'square');
    SeatLayoutManager.createTable(250, 150, 'circle');
    SeatLayoutManager.createTable(400, 200, 'rectangle');
    
    console.log('✅ Created test tables in per_table mode:', SeatLayoutManager.tables);
    
    return SeatLayoutManager.tables;
};

window.reloadLayoutData = function() {
    console.log('📡 Global function: reloadLayoutData triggered');
    
    if (window.SeatLayoutManager) {
        return SeatLayoutManager.reloadDataFromLivewire();
    } else {
        console.error('❌ SeatLayoutManager not available');
        return false;
    }
};

window.initEditMode = function() {
    console.log('🔧 Global function: initEditMode triggered');
    
    if (window.SeatLayoutManager) {
        SeatLayoutManager.forceInitForEdit();
        return true;
    } else {
        console.error('❌ SeatLayoutManager not available');
        return false;
    }
};

console.log('✅ Enhanced Seat Layout Manager with Edit Functionality loaded successfully!');
</script>
@endpush