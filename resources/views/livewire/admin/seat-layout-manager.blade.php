@push('styles')
<style>
/* Seat and Table Element Styles - UPDATED untuk 3 kategori */
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

/* UPDATED: Seat Types - 3 kategori */
.seat-element.regular {
    background-color: #3b82f6; /* Blue - Termurah */
    color: white;
}

.seat-element.gold {
    background-color: #f59e0b; /* Amber/Gold - Menengah */
    color: white;
}

.seat-element.vip {
    background-color: #ef4444; /* Red - Termahal */
    color: white;
}

/* UPDATED: Table Types - 3 kategori */
.table-element.regular {
    background-color: #3b82f6; /* Blue - Termurah */
    color: white;
}

.table-element.gold {
    background-color: #f59e0b; /* Amber/Gold - Menengah */
    color: white;
}

.table-element.vip {
    background-color: #ef4444; /* Red - Termahal */
    color: white;
}

/* Table Shapes */
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

/* UPDATED: Preview Seat Types - 3 kategori */
.preview-seat.regular {
    background-color: #3b82f6;
}

.preview-seat.gold {
    background-color: #f59e0b;
}

.preview-seat.vip {
    background-color: #ef4444;
}

/* UPDATED: Preview Table Types - 3 kategori */
.preview-table.regular {
    background-color: #3b82f6;
}

.preview-table.gold {
    background-color: #f59e0b;
}

.preview-table.vip {
    background-color: #ef4444;
}

.preview-table {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.preview-table.shape-circle {
    border-radius: 1000%;
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
.tool-gold { cursor: crosshair; }
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

/* UPDATED: Table Type Buttons untuk 3 kategori */
.table-type-btn.active.regular {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.table-type-btn.active.gold {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.table-type-btn.active.vip {
    background-color: #ef4444;
    border-color: #ef4444;
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
        background-size: 10px 10px;
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
        min-width: 12px;
        min-height: 12px;
        width: 15px;
        height: 15px;
        border-radius: 4px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 7px;
        font-weight: bold;
        color: white;
        cursor: move;
        z-index: 10;
        border: 1px solid white;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        transition: transform 0.1s, box-shadow 0.1s;
    }
    
    .seat-element:hover {
        transform: scale(1.2);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 100;
    }
    
    /* UPDATED: 3 kategori seat styling */
    .seat-element.vip {
        background-color: #EF4444; /* red-500 - Termahal */
    }
    
    .seat-element.gold {
        background-color: #F59E0B; /* amber-500 - Menengah */
    }
    
    .seat-element.regular {
        background-color: #3B82F6; /* blue-500 - Termurah */
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
    
    /* UPDATED: Table styles untuk 3 kategori */
    .table-element {
        position: absolute;
        border: 1px solid white;
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
        min-width: 20px;
        min-height: 20px;
        width: 30px;
        height: 30px;
        font-size: 6px;
    }

    /* UPDATED: 3 kategori table styling */
    .table-element.vip {
        background-color: #EF4444; /* red-500 - Termahal */
    }
    
    .table-element.gold {
        background-color: #F59E0B; /* amber-500 - Menengah */
    }
    
    .table-element.regular {
        background-color: #3B82F6; /* blue-500 - Termurah */
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
        width: 6px;
        height: 6px;
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

    /* UPDATED: Table Type Selector untuk 3 kategori */
    .table-type-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .table-type-btn {
        padding: 0.5rem;
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        text-align: center;
        font-size: 0.75rem;
    }

    .table-type-btn:hover {
        background-color: #f3f4f6;
    }

    .table-type-btn.active {
        color: white;
        border-color: currentColor;
    }

    .table-type-preview {
        width: 16px;
        height: 16px;
        border-radius: 3px;
        margin: 0 auto;
    }

    .table-type-preview.regular {
        background-color: #3B82F6;
    }

    .table-type-preview.gold {
        background-color: #F59E0B;
    }

    .table-type-preview.vip {
        background-color: #EF4444;
    }

    /* Canvas cursor states */
    #seat-canvas.tool-regular,
    #seat-canvas.tool-gold,
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

    /* RESPONSIVE FONT SIZES BERDASARKAN UKURAN ELEMENT - sama seperti sebelumnya */
    .seat-element[style*="width: 12px"], 
    .seat-element[style*="width: 13px"],
    .seat-element[style*="width: 14px"],
    .seat-element[style*="width: 15px"] {
        font-size: 6px;
        border-radius: 50%;
        border-width: 1px;
    }

    .seat-element[style*="width: 16px"],
    .seat-element[style*="width: 17px"],
    .seat-element[style*="width: 18px"],
    .seat-element[style*="width: 19px"],
    .seat-element[style*="width: 20px"] {
        font-size: 7px;
        border-radius: 50%;
    }

    .seat-element[style*="width: 2"][style*="px"],
    .seat-element[style*="width: 30px"] {
        font-size: 8px;
        border-radius: 50%;
    }

    .seat-element[style*="width: 3"][style*="px"],
    .seat-element[style*="width: 44px"] {
        font-size: 10px;
        border-radius: 4px;
    }

    .seat-element[style*="width: 4"][style*="px"],
    .seat-element[style*="width: 5"][style*="px"],
    .seat-element[style*="width: 6"][style*="px"],
    .seat-element[style*="width: 7"][style*="px"],
    .seat-element[style*="width: 8"][style*="px"],
    .seat-element[style*="width: 9"][style*="px"] {
        font-size: 12px;
        border-radius: 4px;
    }

    .table-element[style*="width: 20px"],
    .table-element[style*="width: 21px"],
    .table-element[style*="width: 22px"],
    .table-element[style*="width: 23px"],
    .table-element[style*="width: 24px"],
    .table-element[style*="width: 25px"],
    .table-element[style*="width: 26px"],
    .table-element[style*="width: 27px"],
    .table-element[style*="width: 28px"],
    .table-element[style*="width: 29px"],
    .table-element[style*="width: 30px"] {
        font-size: 6px;
        border-radius: 2px;
    }

    .table-element[style*="width: 3"][style*="px"],
    .table-element[style*="width: 4"][style*="px"],
    .table-element[style*="width: 50px"] {
        font-size: 7px;
        border-radius: 3px;
    }

    .table-element[style*="width: 5"][style*="px"],
    .table-element[style*="width: 6"][style*="px"],
    .table-element[style*="width: 7"][style*="px"],
    .table-element[style*="width: 80px"] {
        font-size: 8px;
        border-radius: 4px;
    }

    .table-element[style*="width: 8"][style*="px"],
    .table-element[style*="width: 9"][style*="px"],
    .table-element[style*="width: 1"][style*="px"] {
        font-size: 10px;
        border-radius: 6px;
    }

    .seat-element[style*="width: 12px"] .resize-handle,
    .seat-element[style*="width: 13px"] .resize-handle,
    .seat-element[style*="width: 14px"] .resize-handle,
    .seat-element[style*="width: 15px"] .resize-handle,
    .seat-element[style*="width: 16px"] .resize-handle,
    .seat-element[style*="width: 17px"] .resize-handle,
    .seat-element[style*="width: 18px"] .resize-handle,
    .seat-element[style*="width: 19px"] .resize-handle,
    .seat-element[style*="width: 20px"] .resize-handle,
    .table-element[style*="width: 20px"] .resize-handle,
    .table-element[style*="width: 21px"] .resize-handle,
    .table-element[style*="width: 22px"] .resize-handle,
    .table-element[style*="width: 23px"] .resize-handle,
    .table-element[style*="width: 24px"] .resize-handle,
    .table-element[style*="width: 25px"] .resize-handle {
        width: 4px;
        height: 4px;
        opacity: 0.9;
        background-color: #ff6b6b;
    }

    .seat-element[style*="width: 12px"]:hover,
    .seat-element[style*="width: 13px"]:hover,
    .seat-element[style*="width: 14px"]:hover,
    .seat-element[style*="width: 15px"]:hover {
        transform: scale(1.5);
        box-shadow: 0 0 0 2px #4F46E5;
    }

    .seat-element[style*="width: 12px"].selected,
    .seat-element[style*="width: 13px"].selected,
    .seat-element[style*="width: 14px"].selected,
    .seat-element[style*="width: 15px"].selected {
        outline: 1px solid #4F46E5;
        outline-offset: 0px;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
    }

    .seat-element[style*="width: 12px"] span,
    .seat-element[style*="width: 13px"] span,
    .seat-element[style*="width: 14px"] span {
        display: none;
    }

    .seat-element[style*="width: 12px"]::after,
    .seat-element[style*="width: 13px"]::after,
    .seat-element[style*="width: 14px"]::after {
        content: "•";
        font-size: 8px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table-element[style*="width: 20px"] .table-content,
    .table-element[style*="width: 21px"] .table-content,
    .table-element[style*="width: 22px"] .table-content,
    .table-element[style*="width: 23px"] .table-content,
    .table-element[style*="width: 24px"] .table-content,
    .table-element[style*="width: 25px"] .table-content {
        font-size: 5px;
        line-height: 1;
    }

    .table-element[style*="width: 20px"] .table-number,
    .table-element[style*="width: 21px"] .table-number,
    .table-element[style*="width: 22px"] .table-number,
    .table-element[style*="width: 23px"] .table-number,
    .table-element[style*="width: 24px"] .table-number,
    .table-element[style*="width: 25px"] .table-number {
        margin-bottom: 0;
    }

    .table-element[style*="width: 20px"] .table-capacity,
    .table-element[style*="width: 21px"] .table-capacity,
    .table-element[style*="width: 22px"] .table-capacity,
    .table-element[style*="width: 23px"] .table-capacity,
    .table-element[style*="width: 24px"] .table-capacity,
    .table-element[style*="width: 25px"] .table-capacity {
        display: none;
    }

    .background-match-mode {
        border: 2px dashed #3B82F6 !important;
        background-color: rgba(59, 130, 246, 0.05) !important;
    }

    .background-match-mode::before {
        content: "🎯 Background Match Mode";
        position: absolute;
        top: -25px;
        left: 0;
        background: #3B82F6;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        z-index: 1000;
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
                        <span class="text-sm text-gray-600">Enhanced Seat Layout Manager - 3 Kategori</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Layout Kursi</h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Event: <span class="font-semibold">{{ $event->event_name }}</span> - 
                        {{ $event->event_date->format('d M Y') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        ✨ <strong>3 Kategori Tersedia:</strong> Regular (Termurah) • Gold (Menengah) • VIP (Termahal)
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
            
            <!-- Flash Messages - sama seperti sebelumnya -->
        </div>

        <!-- Layout Preview akan otomatis terupdate dengan CSS yang sudah dimodifikasi -->
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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

                    <!-- FIXED: Preview Canvas dengan Height yang Konsisten -->
                    <div class="preview-canvas" style="position: relative; background-color: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 8px; overflow: hidden; height: 300px;">
                        
                        {{-- UPDATED: Background Image Support --}}
                        @if(!empty($layout['background_image']) || !empty($layout['background_image_url']))
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none;">
                                @php
                                    $backgroundUrl = $layout['background_image_url'] ?? 
                                                (!empty($layout['background_image']) ? asset('storage/' . $layout['background_image']) : null);
                                @endphp
                                @if($backgroundUrl)
                                    <img src="{{ $backgroundUrl }}" 
                                         alt="Layout background" 
                                         style="width: 100%; height: 100%; object-fit: contain; opacity: 0.3;">
                                @endif
                            </div>
                        @endif
                        
                        {{-- FIXED: Elements Container dengan Height yang Sama --}}
                        <div style="position: relative; z-index: 10; width: 100%; height: 100%;">
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
                                    
                                    // FIXED: Improved scaling untuk 2-column layout yang lebih besar
                                    if (count($tables) > 0) {
                                        $contentWidth = max(1, $maxX - $minX);
                                        $contentHeight = max(1, $maxY - $minY);
                                        
                                        // UPDATED: Available space untuk 2-column layout (lebih besar)
                                        $availableWidth = 350;  // Increased from 280
                                        $availableHeight = 160; // Keep reasonable height
                                        
                                        $scaleX = $availableWidth / $contentWidth;
                                        $scaleY = $availableHeight / $contentHeight;
                                        $scale = min($scaleX, $scaleY, 1);
                                        
                                        // FIXED: Minimum scale untuk visibility yang lebih baik
                                        $scale = max($scale, 0.3); // Ensure minimum 30% size
                                    } else {
                                        $scale = 1;
                                    }
                                @endphp
                                
                                @foreach($tables as $table)
                                    @php
                                        // FIXED: Better centering calculation
                                        $scaledContentWidth = ($maxX - $minX) * $scale;
                                        $scaledContentHeight = ($maxY - $minY) * $scale;
                                        $offsetX = (350 - $scaledContentWidth) / 2; // Center horizontally
                                        $offsetY = (200 - $scaledContentHeight) / 2; // Center vertically
                                        
                                        $x = (($table['x'] ?? 0) - $minX) * $scale + $offsetX;
                                        $y = (($table['y'] ?? 0) - $minY) * $scale + $offsetY;
                                        $width = max(($table['width'] ?? 120) * $scale, 8);
                                        $height = max(($table['height'] ?? 120) * $scale, 8);
                                        $shape = $table['shape'] ?? 'square';
                                        $capacity = $table['capacity'] ?? 4;
                                        $number = $table['number'] ?? 'T' . ($loop->index + 1);
                                        $tableType = strtolower($table['type'] ?? 'regular');
                                    @endphp
                                    
                                    <div class="preview-element preview-table {{ $tableType }} shape-{{ $shape }}"
                                         style="left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px;">
                                        @if($shape === 'diamond')
                                            <div class="preview-table-content">
                                                <div style="font-size: {{ max(6, $width * 0.1) }}px;">{{ $number }}</div>
                                                <div style="font-size: {{ max(4, $width * 0.08) }}px;">{{ $capacity }}p</div>
                                                @if($width > 15)
                                                    <div style="font-size: {{ max(3, $width * 0.06) }}px;">{{ ucfirst($tableType) }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <div style="font-size: {{ max(6, $width * 0.1) }}px;">{{ $number }}</div>
                                            <div style="font-size: {{ max(4, $width * 0.08) }}px;">{{ $capacity }}p</div>
                                            @if($width > 15)
                                                <div style="font-size: {{ max(3, $width * 0.06) }}px;">{{ ucfirst($tableType) }}</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                                
                                @if(empty($tables))
                                    <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                        Belum ada meja
                                    </div>
                                @endif
                            @else
                                <!-- UPDATED: Seat Mode Preview dengan Scaling yang Diperbaiki -->
                                @php
                                    $customSeats = $config['custom_seats'] ?? [];
                                    
                                    // Count untuk 3 kategori
                                    $regularCount = collect($customSeats)->where('type', 'Regular')->count();
                                    $goldCount = collect($customSeats)->where('type', 'Gold')->count();
                                    $vipCount = collect($customSeats)->where('type', 'VIP')->count();
                                    
                                    $maxX = 0; $maxY = 0; $minX = PHP_INT_MAX; $minY = PHP_INT_MAX;
                                    
                                    foreach ($customSeats as $seat) {
                                        $actualWidth = (int) ($seat['width'] ?? 15);
                                        $actualHeight = (int) ($seat['height'] ?? 15);
                                        
                                        $maxX = max($maxX, ($seat['x'] ?? 0) + $actualWidth);
                                        $maxY = max($maxY, ($seat['y'] ?? 0) + $actualHeight);
                                        $minX = min($minX, $seat['x'] ?? 0);
                                        $minY = min($minY, $seat['y'] ?? 0);
                                    }
                                    
                                    // FIXED: Improved scaling untuk seat preview
                                    if (count($customSeats) > 0) {
                                        $contentWidth = max(1, $maxX - $minX);
                                        $contentHeight = max(1, $maxY - $minY);
                                        
                                        // UPDATED: Available space untuk 2-column layout
                                        $availableWidth = 350;  // Increased
                                        $availableHeight = 160; // Keep reasonable
                                        
                                        $scaleX = $availableWidth / $contentWidth;
                                        $scaleY = $availableHeight / $contentHeight;
                                        $scale = min($scaleX, $scaleY);
                                        
                                        // FIXED: Intelligent minimum scaling
                                        $minPreviewSize = 6; // Slightly larger minimum
                                        $smallestElement = PHP_INT_MAX;
                                        foreach ($customSeats as $seat) {
                                            $actualWidth = (int) ($seat['width'] ?? 15);
                                            $smallestElement = min($smallestElement, $actualWidth);
                                        }
                                        
                                        if ($smallestElement * $scale < $minPreviewSize) {
                                            $scale = $minPreviewSize / $smallestElement;
                                        }
                                        
                                        // FIXED: Reasonable max scale untuk 2-column layout
                                        $scale = min($scale, 4.0); // Allow larger scale for better visibility
                                        
                                    } else {
                                        $scale = 1;
                                        $minX = 0; $minY = 0;
                                    }
                                @endphp
                                
                                @foreach($customSeats as $seat)
                                    @php
                                        $actualWidth = (int) ($seat['width'] ?? 15);
                                        $actualHeight = (int) ($seat['height'] ?? 15);
                                        
                                        // FIXED: Better centering for seats
                                        $scaledContentWidth = ($maxX - $minX) * $scale;
                                        $scaledContentHeight = ($maxY - $minY) * $scale;
                                        $offsetX = (350 - $scaledContentWidth) / 2;
                                        $offsetY = (200 - $scaledContentHeight) / 2;
                                        
                                        $x = (($seat['x'] ?? 0) - $minX) * $scale + $offsetX;
                                        $y = (($seat['y'] ?? 0) - $minY) * $scale + $offsetY;
                                        
                                        $width = max($actualWidth * $scale, 6); // Increased minimum
                                        $height = max($actualHeight * $scale, 6);
                                        
                                        $type = strtolower($seat['type'] ?? 'regular');
                                        $number = $seat['number'] ?? $seat['id'] ?? ($loop->index + 1);
                                        
                                        // FIXED: Dynamic font size based on actual element size
                                        $fontSize = max(5, min(10, $width * 0.4));
                                    @endphp
                                    
                                    <div class="preview-element preview-seat {{ $type }}"
                                        style="left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px; font-size: {{ $fontSize }}px;"
                                        title="{{ ucfirst($type) }} - {{ $number }} ({{ $actualWidth }}x{{ $actualHeight }}px)">
                                        @if($width >= 12)
                                            {{ is_numeric($number) ? $number : substr($number, -2) }}
                                        @else
                                            •
                                        @endif
                                    </div>
                                @endforeach

                                @if(empty($customSeats))
                                    <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                        Belum ada kursi
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- UPDATED: Legend untuk 3 Kategori -->
                    <div class="flex justify-center space-x-4 mt-3 text-xs">
                        @if($sellingMode === 'per_table')
                            @php
                                $shapeCount = [];
                                $typeCount = ['regular' => 0, 'gold' => 0, 'vip' => 0];
                                
                                foreach($config['tables'] ?? [] as $table) {
                                    $shape = $table['shape'] ?? 'square';
                                    $shapeCount[$shape] = ($shapeCount[$shape] ?? 0) + 1;
                                    
                                    $type = strtolower($table['type'] ?? 'regular');
                                    if (isset($typeCount[$type])) {
                                        $typeCount[$type]++;
                                    }
                                }
                            @endphp
                            
                            {{-- Shape Legend --}}
                            @foreach($shapeCount as $shape => $count)
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-purple-500 
                                        {{ $shape === 'circle' ? 'rounded-full' : ($shape === 'diamond' ? 'transform rotate-45' : 'rounded-sm') }}">
                                    </div>
                                    <span class="text-gray-600">{{ ucfirst($shape) }} ({{ $count }})</span>
                                </div>
                            @endforeach
                            
                            {{-- FIXED: Type Legend dengan Layout yang Lebih Compact --}}
                            @if(array_sum($typeCount) > 0)
                                <div class="border-l border-gray-300 pl-2 ml-2">
                                    <div class="flex items-center space-x-2">
                                        @if($typeCount['regular'] > 0)
                                            <div class="flex items-center space-x-1">
                                                <div class="w-3 h-3 bg-blue-500 rounded-sm"></div>
                                                <span class="text-gray-600">R:{{ $typeCount['regular'] }}</span>
                                            </div>
                                        @endif
                                        @if($typeCount['gold'] > 0)
                                            <div class="flex items-center space-x-1">
                                                <div class="w-3 h-3 bg-amber-500 rounded-sm"></div>
                                                <span class="text-gray-600">G:{{ $typeCount['gold'] }}</span>
                                            </div>
                                        @endif
                                        @if($typeCount['vip'] > 0)
                                            <div class="flex items-center space-x-1">
                                                <div class="w-3 h-3 bg-red-500 rounded-sm"></div>
                                                <span class="text-gray-600">V:{{ $typeCount['vip'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- UPDATED: Seat Legend dengan Format yang Compact --}}
                            @if($regularCount > 0)
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-blue-500 rounded-sm"></div>
                                    <span class="text-gray-600">Regular ({{ $regularCount }})</span>
                                </div>
                            @endif
                            @if($goldCount > 0)
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-amber-500 rounded-sm"></div>
                                    <span class="text-gray-600">Gold ({{ $goldCount }})</span>
                                </div>
                            @endif
                            @if($vipCount > 0)
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-red-500 rounded-sm"></div>
                                    <span class="text-gray-600">VIP ({{ $vipCount }})</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- UPDATED: Layout Info yang Lebih Compact untuk 2-Column -->
                <div class="space-y-2 text-sm">
                    @if($sellingMode === 'per_table')
                        @php
                            $totalTables = count($config['tables'] ?? []);
                            $totalCapacity = collect($config['tables'] ?? [])->sum('capacity');
                            
                            // Count tables by type
                            $regularTables = collect($config['tables'] ?? [])->where('type', 'Regular')->count();
                            $goldTables = collect($config['tables'] ?? [])->where('type', 'Gold')->count();
                            $vipTables = collect($config['tables'] ?? [])->where('type', 'VIP')->count();
                            
                            // Revenue calculation untuk 3 kategori
                            $regularTablePrice = $config['regular_table_price'] ?? 500000;
                            $goldTablePrice = $config['gold_table_price'] ?? 700000;
                            $vipTablePrice = $config['vip_table_price'] ?? 1000000;
                            
                            $estimatedRevenue = ($regularTables * $regularTablePrice) + 
                                              ($goldTables * $goldTablePrice) + 
                                              ($vipTables * $vipTablePrice);
                        @endphp
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Meja:</span>
                            <span class="font-medium">{{ $totalTables }} ({{ $totalCapacity }} orang)</span>
                        </div>
                        
                        {{-- FIXED: Compact detail per kategori --}}
                        @if($regularTables + $goldTables + $vipTables > 0)
                            <div class="border-t pt-2 mt-2">
                                <div class="text-xs text-gray-500 mb-1 font-semibold">Breakdown:</div>
                                <div class="grid grid-cols-2 gap-1 text-xs">
                                    @if($regularTables > 0)
                                        <div class="flex justify-between">
                                            <span class="text-blue-600">Regular ({{ $regularTables }}):</span>
                                            <span class="font-medium">{{ number_format($regularTablePrice/1000) }}k</span>
                                        </div>
                                    @endif
                                    @if($goldTables > 0)
                                        <div class="flex justify-between">
                                            <span class="text-amber-600">Gold ({{ $goldTables }}):</span>
                                            <span class="font-medium">{{ number_format($goldTablePrice/1000) }}k</span>
                                        </div>
                                    @endif
                                    @if($vipTables > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">VIP ({{ $vipTables }}):</span>
                                            <span class="font-medium">{{ number_format($vipTablePrice/1000) }}k</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-gray-500">Est. Revenue:</span>
                            <span class="font-medium text-green-600">Rp {{ number_format($estimatedRevenue/1000000, 1) }}M</span>
                        </div>
                    @else
                        @php
                            $totalSeats = count($config['custom_seats'] ?? []);
                            
                            // Revenue calculation untuk 3 kategori
                            $regularPrice = $config['regular_price'] ?? 150000;
                            $goldPrice = $config['gold_price'] ?? 300000;
                            $vipPrice = $config['vip_price'] ?? 500000;
                            
                            $estimatedRevenue = ($regularCount * $regularPrice) + 
                                              ($goldCount * $goldPrice) + 
                                              ($vipCount * $vipPrice);
                        @endphp
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Kursi:</span>
                            <span class="font-medium">{{ $totalSeats }}</span>
                        </div>
                        
                        {{-- FIXED: Compact detail per kategori --}}
                        @if($regularCount + $goldCount + $vipCount > 0)
                            <div class="border-t pt-2 mt-2">
                                <div class="text-xs text-gray-500 mb-1 font-semibold">Breakdown:</div>
                                <div class="grid grid-cols-2 gap-1 text-xs">
                                    @if($regularCount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-blue-600">Regular ({{ $regularCount }}):</span>
                                            <span class="font-medium">{{ number_format($regularPrice/1000) }}k</span>
                                        </div>
                                    @endif
                                    @if($goldCount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-amber-600">Gold ({{ $goldCount }}):</span>
                                            <span class="font-medium">{{ number_format($goldPrice/1000) }}k</span>
                                        </div>
                                    @endif
                                    @if($vipCount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">VIP ({{ $vipCount }}):</span>
                                            <span class="font-medium">{{ number_format($vipPrice/1000) }}k</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-gray-500">Est. Revenue:</span>
                            <span class="font-medium text-green-600">Rp {{ number_format($estimatedRevenue/1000000, 1) }}M</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between border-t pt-2 text-xs">
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($layout['created_at'])->format('d M Y') }}</span>
                        
                        {{-- Background indicator --}}
                        @if(!empty($layout['background_image']) || !empty($layout['background_image_url']))
                            <span class="font-medium text-green-600 ml-2">🖼️ BG</span>
                        @endif
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
        
        <!-- Create/Edit Layout Modal - content yang sama, JavaScript akan handle 3 kategori -->
        @if($showLayoutModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="layout-modal">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                    <div class="relative bg-white rounded-lg shadow-xl transform transition-all w-full max-w-7xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit.prevent="saveLayout" class="h-full flex flex-col">
                            <!-- Header -->
                            <div class="bg-white px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        {{ $editingLayoutId ? 'Edit Layout Kursi' : 'Buat Layout Kursi Baru' }}
                                        <span class="text-sm text-gray-500 ml-2">(3 Kategori: Regular • Gold • VIP)</span>
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
                                <div class="w-96 bg-gray-50 border-r border-gray-200 p-6 overflow-y-auto">
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
                                            
                                            <!-- UPDATED: Table Type Selector untuk 3 kategori -->
                                            <h4 class="text-sm font-medium text-gray-900 mb-3 mt-4">Kategori Meja</h4>
                                            <div class="table-type-selector">
                                                <button type="button" 
                                                        class="table-type-btn active regular" 
                                                        data-type="Regular"
                                                        onclick="SeatLayoutManager.setTableType('Regular')">
                                                    <div class="table-type-preview regular"></div>
                                                    <span>Regular</span>
                                                </button>
                                                <button type="button" 
                                                        class="table-type-btn gold" 
                                                        data-type="Gold"
                                                        onclick="SeatLayoutManager.setTableType('Gold')">
                                                    <div class="table-type-preview gold"></div>
                                                    <span>Gold</span>
                                                </button>
                                                <button type="button" 
                                                        class="table-type-btn vip" 
                                                        data-type="VIP"
                                                        onclick="SeatLayoutManager.setTableType('VIP')">
                                                    <div class="table-type-preview vip"></div>
                                                    <span>VIP</span>
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

                                        <!-- Background Image - sama seperti sebelumnya -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Background Image</h4>
                                            <div class="space-y-3">
                                                
                                                <!-- Current Background Display -->
                                                @if($current_background_image)
                                                    <div class="mb-3">
                                                        <div class="relative">
                                                            <img src="{{ $current_background_image }}" 
                                                                 alt="Background preview" 
                                                                 class="w-full h-20 object-cover rounded border">
                                                            <button type="button" 
                                                                    wire:click="removeBackgroundImage"
                                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                                                ×
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-green-600 mt-1">✅ Background image active</p>
                                                    </div>
                                                @endif
                                                
                                                <!-- Upload Form -->
                                                <div>
                                                    <label for="background_image_upload" class="block text-xs font-medium text-gray-700 mb-1">
                                                        {{ $current_background_image ? 'Replace Background' : 'Upload Background' }}
                                                    </label>
                                                    <input type="file" 
                                                           wire:model="background_image"
                                                           id="background_image_upload"
                                                           accept="image/*"
                                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                    
                                                    @error('background_image') 
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                                    @enderror
                                                    
                                                    <div wire:loading wire:target="background_image" class="mt-2">
                                                        <div class="flex items-center text-xs text-blue-600">
                                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Uploading...
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Info -->
                                                <div class="bg-blue-50 rounded p-2">
                                                    <p class="text-xs text-blue-600">
                                                        <strong>💡 Info:</strong> Background image akan ditampilkan dengan opacity 60% dan fit contain.
                                                    </p>
                                                    <div class="mt-1 text-xs text-blue-500">
                                                        • Max size: 5MB<br>
                                                        • Formats: JPG, PNG, GIF
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- UPDATED: Pricing untuk 3 kategori -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Harga</h4>
                                            <div class="space-y-3" id="pricing-section">
                                                <!-- Pricing inputs will be dynamically updated for 3 categories -->
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
                                
                                <!-- Layout Canvas - sama seperti sebelumnya -->
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
                                    <div class="p-6 basis-1/3 flex-1">
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
                                        <div class="relative  bg-white border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner"
                                            id="seat-canvas"
                                            style="height: 550px; width: 100%; min-height: 550px;">
                                            
                                            <!-- Background Image Container -->
                                            <div id="background-image-container" class="absolute inset-0 z-0 pointer-events-none">
                                                @if($current_background_image)
                                                    <img id="background-image" 
                                                        src="{{ $current_background_image }}" 
                                                        alt="Layout background"
                                                        style="width: 100%; height: 100%; object-fit: contain; opacity: 0.6;">
                                                @endif
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
                                                    <div><strong>2. Kategori:</strong> 
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 mr-1">Regular</span>
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-amber-100 text-amber-800 mr-1">Gold</span>
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-100 text-red-800">VIP</span>
                                                    </div>
                                                    <div><strong>3. Tambah Elemen:</strong> Klik di canvas untuk menambah kursi/meja</div>
                                                    <div><strong>4. Ubah Kategori:</strong> Klik kursi/meja untuk cycle: Regular → Gold → VIP → Regular</div>
                                                    <div><strong>5. Pindahkan:</strong> Drag elemen untuk memindahkan posisi</div>
                                                    <div><strong>6. Resize:</strong> Pilih elemen, lalu drag handle di pojok untuk mengubah ukuran</div>
                                                </div>
                                                <div class="mt-3 p-2 bg-amber-50 rounded text-amber-800">
                                                    <strong>✨ Fitur Baru:</strong> 3 kategori pricing - Regular (termurah), Gold (menengah), VIP (termahal)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
// UPDATED: Enhanced Seat Layout Manager dengan 3 kategori (Regular, Gold, VIP)
window.SeatLayoutManager = {
    // Core properties
    currentTool: 'select',
    sellingMode: 'per_seat',
    seats: [],
    tables: [],
    
    // Counters
    seatCounter: 0,
    tableCounter: 0,
    
    // Selection and interaction
    selectedElements: [],
    
    // Grid settings
    gridSize: 5,
    snapToGrid: true,
    
    // Background image
    backgroundImage: null,
    backgroundOpacity: 0.3,
    
    // Event handlers
    canvasClickHandler: null,
    
    // Zoom settings
    zoomLevel: 1,
    previewZoomLevel: 1,
    
    // UPDATED: Table settings dengan type
    currentTableShape: 'square',
    currentTableType: 'Regular', // Regular, Gold, VIP
    
    // Resize settings
    minSeatSize: 12,
    maxSeatSize: 100,
    minTableSize: 20,
    maxTableSize: 250,
    
    // Default sizes yang bisa disesuaikan
    defaultSeatWidth: 15,
    defaultSeatHeight: 15,
    defaultTableWidth: 30,
    defaultTableHeight: 30,

    // Background matching mode
    backgroundMatchMode: false,
    backgroundScale: 1,
    
    // Initialization state
    initialized: false,
    initRetryCount: 0,
    maxInitRetries: 15,
    
    init() {
        console.log('🚀 Initializing Enhanced Seat Layout Manager with 3 Categories (Regular, Gold, VIP)');
        console.log('Initial data - Seats:', this.seats.length, 'Tables:', this.tables.length);
        this.initialized = false;
        this.initRetryCount = 0;

        // Load initial data first
        this.loadInitialData();
        
        // Check DOM and proceed
        this.checkDOMReady();
        
        console.log('✅ Enhanced initialization started with 3 categories');
    },

    loadInitialData() {
        try {
            console.log('📡 Loading initial data with 3 category support...');
            this.setupSimpleBackground();
            
            // Safely get data from Livewire if available
            if (window.Livewire && window.Livewire.all().length > 0) {
                const component = window.Livewire.all()[0];
                if (component.get) {
                    this.sellingMode = component.get('selling_mode') || 'per_seat';
                    
                    // Load both seats and tables data properly
                    const livewireSeats = component.get('custom_seats') || [];
                    const livewireTables = component.get('tables') || [];
                    
                    // Transform and validate data
                    this.seats = this.transformSeatsData(livewireSeats);
                    this.tables = this.transformTablesData(livewireTables);
                    
                    console.log('📊 Data loaded from Livewire with 3 categories:', {
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
            const serverWidth = parseInt(seat.width);
            const serverHeight = parseInt(seat.height);
            
            const rawWidth = !isNaN(serverWidth) ? serverWidth : 15;
            const rawHeight = !isNaN(serverHeight) ? serverHeight : 15;
            
            const width = Math.max(rawWidth, this.minSeatSize);
            const height = Math.max(rawHeight, this.minSeatSize);
            
            // UPDATED: Ensure seat type is valid for 3 categories
            let seatType = seat.type || 'Regular';
            if (!['Regular', 'Gold', 'VIP'].includes(seatType)) {
                seatType = 'Regular';
            }
            
            const transformed = {
                id: seat.id || `seat_${index + 1}`,
                x: parseInt(seat.x) || 0,
                y: parseInt(seat.y) || 0,
                type: seatType, // Regular, Gold, VIP
                row: seat.row || this.generateSeatRow(seat.y || 0),
                number: seat.number || (index + 1),
                width: width,
                height: height
            };
            
            console.log(`🪑 Transformed seat ${index} (${seatType}):`, { 
                server_data: { width: serverWidth, height: serverHeight },
                final_data: { width: transformed.width, height: transformed.height, type: transformed.type }
            });
            
            return transformed;
        });
    },

    transformTablesData(rawTables) {
        if (!Array.isArray(rawTables)) {
            console.warn('⚠️ Invalid tables data - not an array:', rawTables);
            return [];
        }

        return rawTables.map((table, index) => {
            const serverWidth = parseInt(table.width);
            const serverHeight = parseInt(table.height);
            
            const rawWidth = !isNaN(serverWidth) ? serverWidth : 30;
            const rawHeight = !isNaN(serverHeight) ? serverHeight : 30;
            
            const width = Math.max(rawWidth, this.minTableSize);
            const height = Math.max(rawHeight, this.minTableSize);
            
            // UPDATED: Ensure table type is valid for 3 categories
            let tableType = table.type || 'Regular';
            if (!['Regular', 'Gold', 'VIP'].includes(tableType)) {
                tableType = 'Regular';
            }
            
            const transformed = {
                id: table.id || `table_${index + 1}`,
                x: parseInt(table.x) || 0,
                y: parseInt(table.y) || 0,
                shape: table.shape || 'square',
                capacity: parseInt(table.capacity) || 4,
                number: table.number || `T${index + 1}`,
                type: tableType, // Regular, Gold, VIP
                width: width,
                height: height
            };
            
            console.log(`🍽️ Transformed table ${index} (${tableType}):`, { 
                server_data: { width: serverWidth, height: serverHeight },
                final_data: { width: transformed.width, height: transformed.height, type: transformed.type }
            });
            
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
            }
        }
    },

    proceedWithInit() {
        try {
            console.log('🎯 Proceeding with full initialization...');
            
            this.initializeCounters();
            this.setupCanvas();
            this.setupModeToggle();
            this.setupSimpleBackground();
            this.updateInterface();
            
            // Always render existing data after interface is ready
            this.renderAll();
            
            this.setupKeyboardShortcuts();
            this.updateStatistics();
            
            // Set default tool
            this.setTool('select');
            
            this.initialized = true;
            console.log('✅ Enhanced initialization completed successfully with 3 categories!');
            
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

    // UPDATED: Set table type untuk 3 kategori
    setTableType(type) {
        console.log('🔧 Setting table type to:', type);
        this.currentTableType = type;
        
        // Update button states
        document.querySelectorAll('.table-type-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === type);
        });
        
        console.log('✅ Table type set to:', this.currentTableType);
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

    // UPDATED: Tools untuk 3 kategori
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
            // UPDATED: 3 kategori seat tools
            tools.push(
                {
                    id: 'regular',
                    name: 'Tambah Kursi Regular',
                    color: 'blue',
                    icon: '<div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>'
                },
                {
                    id: 'gold',
                    name: 'Tambah Kursi Gold',
                    color: 'amber',
                    icon: '<div class="w-4 h-4 bg-amber-500 rounded mr-3"></div>'
                },
                {
                    id: 'vip',
                    name: 'Tambah Kursi VIP',
                    color: 'red',
                    icon: '<div class="w-4 h-4 bg-red-500 rounded mr-3"></div>'
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

    // UPDATED: Quick actions untuk 3 kategori
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
                { name: 'Layout Theater (3 kategori)', action: 'createTheaterLayout()' }
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

    // UPDATED: Pricing section untuk 3 kategori
    updatePricingSection() {
        const container = document.getElementById('pricing-section');
        if (!container) return;

        let content = '';
        
        if (this.sellingMode === 'per_table') {
            content = `
                <div>
                    <label for="vip_table_price" class="block text-xs font-medium text-red-700 mb-1">Harga Meja VIP (Termahal)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="vip_table_price" 
                               type="number" 
                               id="vip_table_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="gold_table_price" class="block text-xs font-medium text-amber-700 mb-1">Harga Meja Gold (Menengah)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="gold_table_price" 
                               type="number" 
                               id="gold_table_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-amber-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="regular_table_price" class="block text-xs font-medium text-blue-700 mb-1">Harga Meja Regular (Termurah)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="regular_table_price" 
                               type="number" 
                               id="regular_table_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-blue-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
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
                    <label for="vip_price" class="block text-xs font-medium text-red-700 mb-1">Harga VIP (Termahal)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="vip_price" 
                               type="number" 
                               id="vip_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="gold_price" class="block text-xs font-medium text-amber-700 mb-1">Harga Gold (Menengah)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="gold_price" 
                               type="number" 
                               id="gold_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-amber-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>
                <div>
                    <label for="regular_price" class="block text-xs font-medium text-blue-700 mb-1">Harga Regular (Termurah)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input wire:model.live="regular_price" 
                               type="number" 
                               id="regular_price"
                               step="1000"
                               min="0"
                               onchange="SeatLayoutManager.updateStatistics()"
                               class="block w-full pl-10 border-blue-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
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
                ? 'Pilih kategori & bentuk meja, klik untuk menambah meja, drag untuk memindahkan, resize dengan handle di pojok. Customer memesan langsung per meja.'
                : 'Klik untuk menambah kursi (Regular/Gold/VIP), drag untuk memindahkan, resize dengan handle di pojok. Klik kursi untuk cycle kategori: Regular → Gold → VIP → Regular.';
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

    // UPDATED: Handle canvas click untuk 3 kategori
    handleCanvasClick(x, y) {
        console.log('🎯 Handling canvas click:', { x, y, tool: this.currentTool });
        
        switch (this.currentTool) {
            case 'regular':
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('Regular', x, y);
                }
                break;
            case 'gold':
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('Gold', x, y);
                }
                break;
            case 'vip':
                if (this.sellingMode === 'per_seat') {
                    this.createSeat('VIP', x, y);
                }
                break;
            case 'table':
                if (this.sellingMode === 'per_table') {
                    this.createTable(x, y, this.currentTableShape, this.currentTableType);
                }
                break;
        }
    },

    // UPDATED: Create seat dengan support 3 kategori
    createSeat(type, x, y, tableId = null, width = null, height = null) {
        this.seatCounter++;
        console.log('🪑 Creating seat with 3-category support:', { type, x, y, counter: this.seatCounter });
        
        // Validate type
        if (!['Regular', 'Gold', 'VIP'].includes(type)) {
            type = 'Regular';
        }
        
        const seatWidth = Math.max(width || this.defaultSeatWidth, this.minSeatSize);
        const seatHeight = Math.max(height || this.defaultSeatHeight, this.minSeatSize);
        
        const seat = {
            id: 'seat_' + this.seatCounter,
            x: x,
            y: y,
            type: type, // Regular, Gold, VIP
            row: this.generateSeatRow(y),
            number: this.seatCounter,
            table_id: tableId,
            width: seatWidth,
            height: seatHeight
        };
        
        this.seats.push(seat);
        this.renderSeat(seat);
        this.updateStatistics();
        
        console.log('✅ Seat created with 3-category support:', { 
            width: seatWidth, 
            height: seatHeight, 
            type: type 
        });
    },

    // UPDATED: Create table dengan support 3 kategori
    createTable(x, y, shape = 'square', type = 'Regular') {
        this.tableCounter++;
        console.log('🍽️ Creating table with 3-category support:', { x, y, shape, type, counter: this.tableCounter });
        
        // Validate type
        if (!['Regular', 'Gold', 'VIP'].includes(type)) {
            type = 'Regular';
        }
        
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
            type: type, // Regular, Gold, VIP
            width: width,
            height: height
        };
        
        this.tables.push(table);
        this.renderTable(table);
        this.updateStatistics();
        
        console.log('✅ Table created with 3-category support:', { type: type });
    },

    renderAll() {
        if (!this.initialized && !document.getElementById('seat-canvas')) {
            console.warn('⚠️ Cannot render - canvas not available');
            return;
        }
        
        console.log('🎨 Rendering all elements with 3-category support:', {
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
                    console.log(`🍽️ Rendering table ${index + 1}/${this.tables.length} (${table.type}):`, table);
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
                    console.log(`🪑 Rendering seat ${index + 1}/${this.seats.length} (${seat.type}):`, seat);
                    this.renderSeat(seat);
                } catch (error) {
                    console.error(`❌ Error rendering seat ${seat.id}:`, error);
                }
            });
        }
        
        console.log('✅ All elements rendered successfully with 3-category support');
    },

    // UPDATED: Render table dengan support 3 kategori styling
    renderTable(table) {
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Cannot render table - container not found');
            return;
        }

        const width = table.width || this.defaultTableWidth;
        const height = table.height || this.defaultTableHeight;
        
        const finalWidth = Math.max(width, this.minTableSize);
        const finalHeight = Math.max(height, this.minTableSize);
        
        if (finalWidth !== width || finalHeight !== height) {
            table.width = finalWidth;
            table.height = finalHeight;
        }

        const element = document.createElement('div');
        
        // UPDATED: Add type class untuk styling 3 kategori
        const tableType = table.type || 'Regular';
        element.className = `table-element shape-${table.shape || 'square'} ${tableType.toLowerCase()}`;
        
        element.style.left = (table.x || 0) + 'px';
        element.style.top = (table.y || 0) + 'px';
        element.style.width = finalWidth + 'px';
        element.style.height = finalHeight + 'px';
        element.dataset.tableId = table.id;
        
        // Apply rotation for diamond shape
        if (table.shape === 'diamond') {
            element.style.transform = 'rotate(45deg)';
        }
        
        // Dynamic font size based on actual element size
        const fontSize = this.calculateFontSize(finalWidth, 'table');
        element.style.fontSize = fontSize + 'px';
        
        element.innerHTML = `
            <div class="table-content">
                <div class="table-number">${table.number || 'T1'}</div>
                <div class="table-capacity">${table.capacity || 4} kursi</div>
                <div class="table-type-badge" style="font-size: ${Math.max(fontSize - 2, 6)}px; opacity: 0.8;">${tableType}</div>
            </div>
            ${this.createResizeHandles()}
        `;
        
        this.makeTableInteractive(element);
        container.appendChild(element);
        
        console.log('✅ Table rendered with 3-category support:', { 
            id: table.id, 
            type: tableType,
            width: finalWidth, 
            height: finalHeight,
            fontSize: fontSize 
        });
    },

    // UPDATED: Render seat dengan support 3 kategori styling
    renderSeat(seat) {
        const container = document.getElementById('elements-container');
        if (!container) {
            console.error('❌ Cannot render seat - container not found');
            return;
        }

        const width = seat.width || this.defaultSeatWidth;
        const height = seat.height || this.defaultSeatHeight;
        
        const finalWidth = Math.max(width, this.minSeatSize);
        const finalHeight = Math.max(height, this.minSeatSize);
        
        if (finalWidth !== width || finalHeight !== height) {
            seat.width = finalWidth;
            seat.height = finalHeight;
        }

        const element = document.createElement('div');
        const isTableSeat = seat.table_id !== null && seat.table_id !== undefined;
        
        // UPDATED: Add type class untuk styling 3 kategori
        const seatType = seat.type || 'Regular';
        element.className = `seat-element ${seatType.toLowerCase()} ${isTableSeat ? 'table-seat' : ''}`;
        
        element.style.left = (seat.x || 0) + 'px';
        element.style.top = (seat.y || 0) + 'px';
        element.style.width = finalWidth + 'px';
        element.style.height = finalHeight + 'px';
        element.dataset.seatId = seat.id;
        
        // Dynamic font size based on actual element size
        const fontSize = this.calculateFontSize(finalWidth, 'seat');
        element.style.fontSize = fontSize + 'px';
        
        element.innerHTML = `
            <span>${seat.number || 1}</span>
            ${this.createResizeHandles()}
        `;
        
        this.makeSeatInteractive(element);
        container.appendChild(element);
        
        console.log('✅ Seat rendered with 3-category support:', { 
            id: seat.id, 
            type: seatType,
            width: finalWidth, 
            height: finalHeight,
            fontSize: fontSize 
        });
    },

    setupSimpleBackground() {
        console.log('🖼️ Setting up simple background image');
        
        // Load existing background
        const img = document.getElementById('background-image');
        if (img) {
            this.backgroundImage = img.src;
            console.log('📷 Background image loaded:', this.backgroundImage);
        }
    },

    setSimpleBackground(imageUrl) {
        console.log('🖼️ Setting simple background image:', imageUrl);
        
        this.backgroundImage = imageUrl;
        
        const container = document.getElementById('background-image-container');
        if (container && imageUrl) {
            container.innerHTML = `
                <img id="background-image" 
                     src="${imageUrl}" 
                     alt="Layout background"
                     style="width: 100%; height: 100%; object-fit: contain; opacity: 0.6;">
            `;
            console.log('✅ Background image set');
        } else if (container) {
            container.innerHTML = '';
            console.log('🗑️ Background image cleared');
        }
    },

    removeSimpleBackground() {
        console.log('🗑️ Removing background image');
        
        this.backgroundImage = null;
        
        const container = document.getElementById('background-image-container');
        if (container) {
            container.innerHTML = '';
        }
        
        console.log('✅ Background image removed');
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

    calculateFontSize(elementWidth, type) {
        let fontSize;
        
        if (type === 'seat') {
            if (elementWidth <= 35) {
                fontSize = 9;   // Very small seats
            } else if (elementWidth <= 44) {
                fontSize = 11;  // Normal seats
            } else if (elementWidth <= 60) {
                fontSize = 12;  // Large seats
            } else {
                fontSize = 14;  // Very large seats
            }
        } else { // table
            if (elementWidth <= 70) {
                fontSize = 9;   // Small tables
            } else if (elementWidth <= 120) {
                fontSize = 11;  // Normal tables
            } else if (elementWidth <= 160) {
                fontSize = 12;  // Large tables
            } else {
                fontSize = 14;  // Very large tables
            }
        }
        
        return Math.max(fontSize, 8); // Minimum 8px font
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
                
                console.log('🔧 Starting resize:', { direction, type, startWidth, startHeight });
                
                const handleMouseMove = (e) => {
                    const deltaX = e.clientX - startX;
                    const deltaY = e.clientY - startY;
                    
                    let newWidth = startWidth;
                    let newHeight = startHeight;
                    let newLeft = startLeft;
                    let newTop = startTop;
                    
                    // Calculate new dimensions
                    switch (direction) {
                        case 'se': newWidth = startWidth + deltaX; newHeight = startHeight + deltaY; break;
                        case 'sw': newWidth = startWidth - deltaX; newHeight = startHeight + deltaY; newLeft = startLeft + deltaX; break;
                        case 'ne': newWidth = startWidth + deltaX; newHeight = startHeight - deltaY; newTop = startTop + deltaY; break;
                        case 'nw': newWidth = startWidth - deltaX; newHeight = startHeight - deltaY; newLeft = startLeft + deltaX; newTop = startTop + deltaY; break;
                        case 'n': newHeight = startHeight - deltaY; newTop = startTop + deltaY; break;
                        case 's': newHeight = startHeight + deltaY; break;
                        case 'w': newWidth = startWidth - deltaX; newLeft = startLeft + deltaX; break;
                        case 'e': newWidth = startWidth + deltaX; break;
                    }
                    
                    // Apply constraints
                    if (type === 'seat') {
                        newWidth = Math.max(this.minSeatSize, Math.min(this.maxSeatSize, newWidth));
                        newHeight = Math.max(this.minSeatSize, Math.min(this.maxSeatSize, newHeight));
                        
                        // Keep seats roughly square
                        const avgSize = (newWidth + newHeight) / 2;
                        newWidth = avgSize;
                        newHeight = avgSize;
                    } else {
                        newWidth = Math.max(this.minTableSize, Math.min(this.maxTableSize, newWidth));
                        newHeight = Math.max(this.minTableSize, Math.min(this.maxTableSize, newHeight));
                    }
                    
                    // Grid snapping
                    if (this.snapToGrid) {
                        const gridSize = newWidth < 20 ? 2 : this.gridSize;
                        newLeft = Math.round(newLeft / gridSize) * gridSize;
                        newTop = Math.round(newTop / gridSize) * gridSize;
                        newWidth = Math.round(newWidth / gridSize) * gridSize;
                        newHeight = Math.round(newHeight / gridSize) * gridSize;
                    }
                    
                    // Ensure minimum after grid snapping
                    newWidth = Math.max(newWidth, type === 'seat' ? this.minSeatSize : this.minTableSize);
                    newHeight = Math.max(newHeight, type === 'seat' ? this.minSeatSize : this.minTableSize);
                    
                    // Update element styles
                    element.style.width = newWidth + 'px';
                    element.style.height = newHeight + 'px';
                    element.style.left = newLeft + 'px';
                    element.style.top = newTop + 'px';
                    
                    // Update font size
                    this.updateElementFontSize(element, newWidth, type);
                    
                    // Update data
                    if (type === 'table') {
                        const table = this.tables.find(t => t.id === element.dataset.tableId);
                        if (table) {
                            table.width = newWidth; table.height = newHeight;
                            table.x = newLeft; table.y = newTop;
                        }
                    } else {
                        const seat = this.seats.find(s => s.id === element.dataset.seatId);
                        if (seat) {
                            seat.width = newWidth; seat.height = newHeight;
                            seat.x = newLeft; seat.y = newTop;
                        }
                    }
                };
                
                const handleMouseUp = () => {
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                    document.body.style.cursor = 'default';
                    console.log('✅ Resize completed');
                };
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            });
        });
    },

    updateElementFontSize(element, width, type) {
        let fontSize;
        
        if (type === 'seat') {
            if (width <= 15) {
                fontSize = 6;
                element.innerHTML = '<span>•</span>' + this.createResizeHandles();
            } else if (width <= 20) {
                fontSize = 7;
            } else if (width <= 30) {
                fontSize = 8;
            } else if (width <= 44) {
                fontSize = 10;
            } else {
                fontSize = 12;
            }
        } else { // table
            if (width <= 25) {
                fontSize = 6;
                element.querySelector('.table-content').innerHTML = '<div style="font-size: 6px;">T</div>';
            } else if (width <= 40) {
                fontSize = 7;
            } else if (width <= 60) {
                fontSize = 8;
            } else {
                fontSize = 10;
            }
        }
        
        element.style.fontSize = fontSize + 'px';
        
        // Update resize handles size
        const handles = element.querySelectorAll('.resize-handle');
        handles.forEach(handle => {
            if (width <= 20) {
                handle.style.width = '4px';
                handle.style.height = '4px';
                handle.style.opacity = '0.9';
                handle.style.backgroundColor = '#ff6b6b';
            } else {
                handle.style.width = '8px';
                handle.style.height = '8px';
                handle.style.opacity = '';
                handle.style.backgroundColor = '';
            }
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

    // UPDATED: Handle element click untuk 3 kategori cycling
    handleElementClick(element, type) {
        if (this.currentTool === 'delete') {
            if (type === 'table') {
                this.deleteTable(element.dataset.tableId);
            } else {
                this.deleteSeat(element.dataset.seatId);
            }
        } else if (this.currentTool === 'select') {
            if (type === 'seat' && this.sellingMode === 'per_seat' && !element.dataset.seatId.includes('table')) {
                // UPDATED: Cycle melalui 3 kategori: Regular → Gold → VIP → Regular
                this.cycleSeatType(element.dataset.seatId);
            } else if (type === 'table' && this.sellingMode === 'per_table') {
                // UPDATED: Cycle melalui 3 kategori table: Regular → Gold → VIP → Regular
                this.cycleTableType(element.dataset.tableId);
            }
            this.toggleSelection(element);
        }
    },

    // UPDATED: Cycle seat type untuk 3 kategori
    cycleSeatType(seatId) {
        const seat = this.seats.find(s => s.id === seatId);
        if (seat) {
            // Cycle: Regular → Gold → VIP → Regular
            switch (seat.type) {
                case 'Regular':
                    seat.type = 'Gold';
                    break;
                case 'Gold':
                    seat.type = 'VIP';
                    break;
                case 'VIP':
                    seat.type = 'Regular';
                    break;
                default:
                    seat.type = 'Regular';
            }
            
            const element = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (element) {
                // Remove old type classes
                element.classList.remove('regular', 'gold', 'vip');
                // Add new type class
                element.classList.add(seat.type.toLowerCase());
                
                console.log('🔄 Seat type cycled:', seatId, 'to', seat.type);
            }
            
            this.updateStatistics();
        }
    },

    // UPDATED: Cycle table type untuk 3 kategori
    cycleTableType(tableId) {
        const table = this.tables.find(t => t.id === tableId);
        if (table) {
            // Cycle: Regular → Gold → VIP → Regular
            switch (table.type) {
                case 'Regular':
                    table.type = 'Gold';
                    break;
                case 'Gold':
                    table.type = 'VIP';
                    break;
                case 'VIP':
                    table.type = 'Regular';
                    break;
                default:
                    table.type = 'Regular';
            }
            
            const element = document.querySelector(`[data-table-id="${tableId}"]`);
            if (element) {
                // Remove old type classes
                element.classList.remove('regular', 'gold', 'vip');
                // Add new type class
                element.classList.add(table.type.toLowerCase());
                
                // Update type badge if exists
                const typeBadge = element.querySelector('.table-type-badge');
                if (typeBadge) {
                    typeBadge.textContent = table.type;
                }
                
                console.log('🔄 Table type cycled:', tableId, 'to', table.type);
            }
            
            this.updateStatistics();
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

    // UPDATED: Quick layout generators untuk 3 kategori
    createGridLayout(rows, cols) {
        if (!confirm(`Buat layout grid ${rows}x${cols} dengan 3 kategori? Ini akan menghapus semua elemen yang ada.`)) return;

        this.clearAll();
        
        const startX = 50;
        const startY = 50;
        const seatSpacing = 50;
        
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const x = startX + (col * seatSpacing);
                const y = startY + (row * seatSpacing);
                
                // UPDATED: Distribute 3 kategori secara proporsional
                let type = 'Regular'; // Default
                if (row < 2) {
                    type = 'VIP'; // 2 baris depan VIP
                } else if (row < 5) {
                    type = 'Gold'; // 3 baris tengah Gold
                } // Sisanya Regular
                
                this.createSeat(type, x, y);
            }
        }
        
        console.log('✅ Grid layout created with 3 categories');
    },

    // UPDATED: Theater layout dengan 3 kategori
    createTheaterLayout() {
        if (!confirm('Buat layout theater dengan 3 kategori? Ini akan menghapus semua elemen yang ada.')) return;

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
                
                // UPDATED: Theater layout dengan 3 kategori
                let type = 'Regular'; // Default
                if (row < 3) {
                    type = 'VIP'; // 3 baris depan VIP (terdekat panggung)
                } else if (row < 7) {
                    type = 'Gold'; // 4 baris tengah Gold
                } // Baris belakang Regular
                
                this.createSeat(type, x, y);
            }
        }
        
        console.log('✅ Theater layout created with 3 categories');
    },

    // UPDATED: Restaurant layout dengan 3 kategori
    createRestaurantLayout() {
        if (!confirm('Buat layout restaurant dengan 3 kategori? Ini akan menghapus semua elemen yang ada.')) return;

        this.clearAll();
        
        const shapes = ['square', 'circle', 'rectangle'];
        const types = ['Regular', 'Gold', 'VIP'];
        const positions = [
            {x: 100, y: 100}, {x: 300, y: 100}, {x: 500, y: 100}, {x: 700, y: 100},
            {x: 100, y: 200}, {x: 300, y: 200}, {x: 500, y: 200}, {x: 700, y: 200},
            {x: 100, y: 300}, {x: 300, y: 300}, {x: 500, y: 300}, {x: 700, y: 300},
            {x: 100, y: 400}, {x: 300, y: 400}, {x: 500, y: 400}, {x: 700, y: 400},
            {x: 200, y: 450}, {x: 400, y: 450}, {x: 600, y: 450}, {x: 800, y: 450}
        ];
        
        positions.forEach((pos, index) => {
            const shape = shapes[index % shapes.length];
            const type = types[index % types.length];
            this.createTable(pos.x, pos.y, shape, type);
        });
        
        console.log('✅ Restaurant layout created with 3 categories');
    },

    // UPDATED: Banquet layout dengan 3 kategori
    createBanquetLayout() {
        if (!confirm('Buat layout banquet dengan 3 kategori? Ini akan menghapus semua elemen yang ada.')) return;

        this.clearAll();
        
        const centerX = 400;
        const centerY = 300;
        const shapes = ['circle', 'square', 'rectangle', 'diamond'];
        
        // Inner circle - 10 tables (VIP - dekat pusat)
        for (let i = 0; i < 10; i++) {
            const angle = (2 * Math.PI * i) / 10;
            const x = centerX + Math.cos(angle) * 150;
            const y = centerY + Math.sin(angle) * 150;
            const shape = shapes[i % shapes.length];
            this.createTable(x, y, shape, 'VIP');
        }
        
        // Middle circle - 15 tables (Gold)
        for (let i = 0; i < 15; i++) {
            const angle = (2 * Math.PI * i) / 15;
            const x = centerX + Math.cos(angle) * 200;
            const y = centerY + Math.sin(angle) * 200;
            const shape = shapes[i % shapes.length];
            this.createTable(x, y, shape, 'Gold');
        }
        
        // Outer circle - 20 tables (Regular)
        for (let i = 0; i < 20; i++) {
            const angle = (2 * Math.PI * i) / 20;
            const x = centerX + Math.cos(angle) * 250;
            const y = centerY + Math.sin(angle) * 250;
            const shape = shapes[i % shapes.length];
            this.createTable(x, y, shape, 'Regular');
        }
        
        console.log('✅ Banquet layout created with 3 categories (VIP inner, Gold middle, Regular outer)');
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

    // UPDATED: Statistics untuk 3 kategori
    updateStatistics() {
        const totalTables = this.tables.length;

        // UPDATED: Get prices untuk 3 kategori
        const regularPrice = parseInt(document.getElementById('regular_price')?.value || '150000');
        const goldPrice = parseInt(document.getElementById('gold_price')?.value || '300000');
        const vipPrice = parseInt(document.getElementById('vip_price')?.value || '500000');
        
        const regularTablePrice = parseInt(document.getElementById('regular_table_price')?.value || '500000');
        const goldTablePrice = parseInt(document.getElementById('gold_table_price')?.value || '700000');
        const vipTablePrice = parseInt(document.getElementById('vip_table_price')?.value || '1000000');

        let estimatedRevenue = 0;
        let statisticsHTML = '';
        
        if (this.sellingMode === 'per_table') {
            let totalCapacity = 0;
            
            // UPDATED: Count berdasarkan 3 kategori
            const regularTables = this.tables.filter(t => (t.type || 'Regular') === 'Regular').length;
            const goldTables = this.tables.filter(t => (t.type || 'Regular') === 'Gold').length;
            const vipTables = this.tables.filter(t => (t.type || 'Regular') === 'VIP').length;
            
            this.tables.forEach(table => {
                totalCapacity += table.capacity || 4;
            });

            // UPDATED: Revenue calculation untuk 3 kategori
            estimatedRevenue = (regularTables * regularTablePrice) + 
                             (goldTables * goldTablePrice) + 
                             (vipTables * vipTablePrice);
            
            statisticsHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Meja:</span>
                    <span class="font-medium">${totalTables}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Kapasitas:</span>
                    <span class="font-medium">${totalCapacity} orang</span>
                </div>
                <hr class="my-2">
                <div class="text-xs text-gray-500 mb-1">Kategori Meja:</div>
                <div class="flex justify-between text-xs">
                    <span class="text-blue-600">Regular:</span>
                    <span class="font-medium">${regularTables}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-amber-600">Gold:</span>
                    <span class="font-medium">${goldTables}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-red-600">VIP:</span>
                    <span class="font-medium">${vipTables}</span>
                </div>
            `;
        } else {
            // UPDATED: Count berdasarkan 3 kategori seat
            const regularCount = this.seats.filter(s => (s.type || 'Regular') === 'Regular').length;
            const goldCount = this.seats.filter(s => (s.type || 'Regular') === 'Gold').length;
            const vipCount = this.seats.filter(s => (s.type || 'Regular') === 'VIP').length;
            const totalSeats = this.seats.length;

            // UPDATED: Revenue calculation untuk 3 kategori
            estimatedRevenue = (regularCount * regularPrice) + 
                             (goldCount * goldPrice) + 
                             (vipCount * vipPrice);
            
            statisticsHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Kursi:</span>
                    <span class="font-medium">${totalSeats}</span>
                </div>
                <hr class="my-2">
                <div class="text-xs text-gray-500 mb-1">Kategori Kursi:</div>
                <div class="flex justify-between text-xs">
                    <span class="text-blue-600">Regular:</span>
                    <span class="font-medium">${regularCount}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-amber-600">Gold:</span>
                    <span class="font-medium">${goldCount}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-red-600">VIP:</span>
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

    // UPDATED: Enhanced validation dengan 3 kategori
    validateLayout() {
        console.log('🔍 Validating layout data with 3-category support...');
        
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
            
            // Validate table data dengan 3 kategori
            this.tables.forEach((table, index) => {
                if (!table.x && table.x !== 0 || !table.y && table.y !== 0) {
                    errors.push(`Meja ${index + 1} tidak memiliki posisi yang valid`);
                }
                
                // Validate table type
                if (!['Regular', 'Gold', 'VIP'].includes(table.type || 'Regular')) {
                    console.warn(`⚠️ Table ${index + 1} has invalid type, setting to Regular`);
                    table.type = 'Regular';
                }
                
                // Validate minimum size
                if ((table.width || 0) < this.minTableSize) {
                    table.width = this.minTableSize;
                }
                if ((table.height || 0) < this.minTableSize) {
                    table.height = this.minTableSize;
                }
            });
            
            console.log('📊 Table validation with 3-category support:', {
                tables_count: this.tables.length,
                regular_tables: this.tables.filter(t => (t.type || 'Regular') === 'Regular').length,
                gold_tables: this.tables.filter(t => (t.type || 'Regular') === 'Gold').length,
                vip_tables: this.tables.filter(t => (t.type || 'Regular') === 'VIP').length
            });
        } else {
            if (this.seats.length === 0) {
                errors.push('Layout harus memiliki minimal satu kursi');
                console.error('❌ No seats found in per_seat mode');
            }
            
            // Validate seat data dengan 3 kategori
            this.seats.forEach((seat, index) => {
                if (!seat.x && seat.x !== 0 || !seat.y && seat.y !== 0) {
                    errors.push(`Kursi ${index + 1} tidak memiliki posisi yang valid`);
                }
                
                // Validate seat type
                if (!['Regular', 'Gold', 'VIP'].includes(seat.type || 'Regular')) {
                    console.warn(`⚠️ Seat ${index + 1} has invalid type, setting to Regular`);
                    seat.type = 'Regular';
                }
                
                // Validate minimum size
                if ((seat.width || 0) < this.minSeatSize) {
                    seat.width = this.minSeatSize;
                }
                if ((seat.height || 0) < this.minSeatSize) {
                    seat.height = this.minSeatSize;
                }
            });
            
            console.log('📊 Seat validation with 3-category support:', {
                seats_count: this.seats.length,
                regular_seats: this.seats.filter(s => (s.type || 'Regular') === 'Regular').length,
                gold_seats: this.seats.filter(s => (s.type || 'Regular') === 'Gold').length,
                vip_seats: this.seats.filter(s => (s.type || 'Regular') === 'VIP').length
            });
        }
        
        if (errors.length > 0) {
            console.error('❌ Validation errors:', errors);
            alert('Error validasi layout:\n\n' + errors.join('\n'));
            return false;
        }
        
        console.log('✅ Layout validation passed with 3-category support');
        return true;
    },

    // UPDATED: Enhanced sync dengan 3 kategori
    syncWithLivewire() {
        console.log('📡 Syncing data with Livewire (3-category support)...');
        
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
        
        console.log(`🔍 Selling mode: ${this.sellingMode}`);
        console.log(`🔢 Data count: ${this.sellingMode === 'per_seat' ? this.seats.length + ' seats' : this.tables.length + ' tables'}`);
        
        // Transform data based on selling mode dengan 3 kategori
        let dataToSync;
        
        if (this.sellingMode === 'per_table') {
            const transformedTables = this.tables.map(table => {
                return {
                    id: table.id,
                    x: table.x,
                    y: table.y,
                    shape: table.shape || 'square',
                    capacity: table.capacity || 4,
                    number: table.number || ('T' + table.id.replace('table_', '')),
                    type: table.type || 'Regular', // UPDATED: Include type
                    width: table.width || 120,
                    height: table.height || 120
                };
            });
            
            dataToSync = {
                selling_mode: 'per_table',
                tables: transformedTables,
                custom_seats: []
            };
            
            console.log('📊 Tables data to sync with 3 categories:', {
                tables_count: transformedTables.length,
                regular_count: transformedTables.filter(t => t.type === 'Regular').length,
                gold_count: transformedTables.filter(t => t.type === 'Gold').length,
                vip_count: transformedTables.filter(t => t.type === 'VIP').length,
                sample_table: transformedTables.length > 0 ? transformedTables[0] : null
            });
        } else {
            const transformedSeats = this.seats.map(seat => {
                return {
                    id: seat.id,
                    x: seat.x,
                    y: seat.y,
                    type: seat.type || 'Regular', // UPDATED: Include type
                    row: seat.row || this.generateSeatRow(seat.y),
                    number: seat.number || seat.id.replace('seat_', ''),
                    width: seat.width || 44,
                    height: seat.height || 44
                };
            });
            
            dataToSync = {
                selling_mode: 'per_seat',
                custom_seats: transformedSeats,
                tables: []
            };
            
            console.log('📊 Seats data to sync with 3 categories:', {
                seats_count: transformedSeats.length,
                regular_count: transformedSeats.filter(s => s.type === 'Regular').length,
                gold_count: transformedSeats.filter(s => s.type === 'Gold').length,
                vip_count: transformedSeats.filter(s => s.type === 'VIP').length,
                sample_seat: transformedSeats.length > 0 ? transformedSeats[0] : null
            });
        }
        
        // Try to access Livewire component
        let component = null;
        
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
                if (typeof component.set === 'function') {
                    component.set('selling_mode', dataToSync.selling_mode);
                    
                    if (this.sellingMode === 'per_table') {
                        component.set('tables', dataToSync.tables);
                        component.set('custom_seats', []);
                    } else {
                        component.set('custom_seats', dataToSync.custom_seats);
                        component.set('tables', []);
                    }
                    
                    console.log('✅ Data synced via set() method with 3-category support');
                    
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

    // Manual fallback untuk updating form inputs
    updateFormInputsManually(dataToSync) {
        console.log('🔧 Using manual form input update...');
        
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
        
        console.log('✅ Manual form inputs created with 3-category support:', {
            mode: dataToSync.selling_mode,
            seats_count: (dataToSync.custom_seats || []).length,
            tables_count: (dataToSync.tables || []).length
        });
    },
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM Content Loaded - Enhanced 3-Category Support');
    
    setTimeout(() => {
        if (window.SeatLayoutManager) {
            console.log('🔄 Starting Enhanced SeatLayoutManager initialization with 3 categories...');
            SeatLayoutManager.init();
        } else {
            console.error('❌ SeatLayoutManager not found!');
        }
    }, 100);
});

// Initialize when modal opens
document.addEventListener('livewire:initialized', () => {
    console.log('⚡ Livewire initialized - Enhanced 3-Category Support');
    
    Livewire.on('background-uploaded', (data) => {
        console.log('📷 Background uploaded:', data);
        if (window.SeatLayoutManager && data[0]?.url) {
            SeatLayoutManager.setSimpleBackground(data[0].url);
        }
    });

    Livewire.on('background-removed', () => {
        console.log('📷 Background removed');
        if (window.SeatLayoutManager) {
            SeatLayoutManager.removeSimpleBackground();
        }
    });

    Livewire.on('layout-data-loaded', (data) => {
        console.log('📡 Layout data loaded event received with 3-category support:', data);
        
        if (window.SeatLayoutManager) {
            SeatLayoutManager.sellingMode = data[0].selling_mode;
            
            // Transform dan validate data saat loading
            SeatLayoutManager.seats = SeatLayoutManager.transformSeatsData(data[0].custom_seats || []);
            SeatLayoutManager.tables = SeatLayoutManager.transformTablesData(data[0].tables || []);

            if (data[0].background_image) {
                SeatLayoutManager.setSimpleBackground(data[0].background_image);
            }
            
            console.log('📊 Data updated in SeatLayoutManager with 3-category support:', {
                mode: SeatLayoutManager.sellingMode,
                seats: SeatLayoutManager.seats.length,
                tables: SeatLayoutManager.tables.length,
                seat_categories: {
                    regular: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'Regular').length,
                    gold: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'Gold').length,
                    vip: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'VIP').length
                },
                table_categories: {
                    regular: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'Regular').length,
                    gold: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'Gold').length,
                    vip: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'VIP').length
                }
            });
            
            // Wait for modal to be fully rendered, then initialize
            setTimeout(() => {
                if (document.getElementById('seat-canvas')) {
                    console.log('🔄 Initializing SeatLayoutManager for edit mode with 3-category support...');
                    SeatLayoutManager.forceInitForEdit();
                } else {
                    console.warn('⚠️ Canvas not found after data load event');
                }
            }, 500);
        }
    });
    
    Livewire.hook('morph.updated', () => {
        console.log('🔄 Livewire DOM updated - Re-initializing with 3-category support...');
        setTimeout(() => {
            if (window.SeatLayoutManager && document.getElementById('seat-canvas')) {
                SeatLayoutManager.forceInitForEdit();
            }
        }, 300);
    });
});

// Enhanced form submission handler with 3-category validation
document.addEventListener('submit', function(e) {
    const form = e.target.closest('form');
    console.log('📨 Form submission detected with 3-category support:', form);
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
        
        console.log('🔍 Pre-submission validation with 3-category support...');
        const syncSuccess = SeatLayoutManager.syncWithLivewire();
        
        if (syncSuccess) {
            console.log('✅ Validation passed, submitting form with 3-category data...');
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
    console.log('💾 Manual save layout triggered with 3-category support...');
    
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        alert('Layout Manager tidak tersedia');
        return;
    }
    
    const syncSuccess = SeatLayoutManager.syncWithLivewire();
    
    if (syncSuccess) {
        console.log('✅ Data synced with 3-category support, proceeding with save...');
        
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

// Global helper functions dengan 3-category support
window.debugSeatManager = function() {
    const seatCategories = {
        regular: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'Regular').length,
        gold: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'Gold').length,
        vip: SeatLayoutManager.seats.filter(s => (s.type || 'Regular') === 'VIP').length
    };
    
    const tableCategories = {
        regular: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'Regular').length,
        gold: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'Gold').length,
        vip: SeatLayoutManager.tables.filter(t => (t.type || 'Regular') === 'VIP').length
    };
    
    console.log('🐛 DEBUG INFO with 3-Category Support:', {
        initialized: SeatLayoutManager.initialized,
        canvasExists: !!document.getElementById('seat-canvas'),
        containerExists: !!document.getElementById('elements-container'),
        modalVisible: !!document.querySelector('[wire\\:key="layout-modal"]'),
        livewireAvailable: !!(window.Livewire && window.Livewire.all().length > 0),
        currentData: {
            mode: SeatLayoutManager.sellingMode,
            seats: SeatLayoutManager.seats.length,
            tables: SeatLayoutManager.tables.length,
            seat_categories: seatCategories,
            table_categories: tableCategories
        }
    });
};

window.testCreate3CategorySeats = function() {
    console.log('🧪 Testing 3-category seat creation...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.createSeat('Regular', 100, 100);
        SeatLayoutManager.createSeat('Gold', 150, 100);
        SeatLayoutManager.createSeat('VIP', 200, 100);
        console.log('✅ Test seats created with all 3 categories');
    }
};

window.testCreate3CategoryTables = function() {
    console.log('🧪 Testing 3-category table creation...');
    if (window.SeatLayoutManager) {
        SeatLayoutManager.createTable(200, 200, 'square', 'Regular');
        SeatLayoutManager.createTable(300, 200, 'circle', 'Gold');
        SeatLayoutManager.createTable(400, 200, 'rectangle', 'VIP');
        console.log('✅ Test tables created with all 3 categories');
    }
};

window.force3CategoryMode = function() {
    if (!window.SeatLayoutManager) {
        console.error('❌ SeatLayoutManager not available');
        return;
    }
    
    // Set to seat mode
    SeatLayoutManager.setSellingMode('per_seat');
    
    // Clear existing elements
    SeatLayoutManager.clearAll();
    
    // Create sample seats with all 3 categories
    SeatLayoutManager.createSeat('Regular', 100, 100);
    SeatLayoutManager.createSeat('Gold', 150, 100);
    SeatLayoutManager.createSeat('VIP', 200, 100);
    SeatLayoutManager.createSeat('Regular', 100, 150);
    SeatLayoutManager.createSeat('Gold', 150, 150);
    SeatLayoutManager.createSeat('VIP', 200, 150);
    
    console.log('✅ Created test layout with all 3 categories:', {
        regular: SeatLayoutManager.seats.filter(s => s.type === 'Regular').length,
        gold: SeatLayoutManager.seats.filter(s => s.type === 'Gold').length,
        vip: SeatLayoutManager.seats.filter(s => s.type === 'VIP').length
    });
    
    return SeatLayoutManager.seats;
};

console.log('✅ Enhanced Seat Layout Manager with 3-Category Support (Regular, Gold, VIP) loaded successfully!');
</script>
@endpush