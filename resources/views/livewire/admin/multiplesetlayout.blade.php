@push('styles')
<style>
    .tool-btn.active {
            @apply border-2;
        }
        
        #seat-canvas {
            background-image: url(''); /* Gambar akan diatur melalui JavaScript */
            background-size: contain; /* Mengatur ukuran gambar agar menutupi area */
            background-position: center; /* Memusatkan gambar */
            position: relative;
        }
        
        .seat-element {
            touch-action: none;
            user-select: none;
            position: absolute;
            width: 44px;
            height: 44px;
            border-radius: 4px;
            text-align: center;
            line-height: 44px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            cursor: move;
            z-index: 1;
            border: 2px solid white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: transform 0.1s, box-shadow 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .seat-element.rectangle {
            width: 60px;
            height: 30px;
            border-radius: 4px;
            line-height: 30px;
        }
        
        .seat-element.circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            line-height: 40px;
        }
        
        .seat-element.diamond {
            width: 40px;
            height: 40px;
            transform: rotate(45deg);
            line-height: 40px;
        }
        
        .seat-element.diamond .seat-text {
            transform: rotate(-45deg);
            display: inline-block;
        }
        
        .seat-element.square {
            width: 40px;
            height: 40px;
            border-radius: 0;
            line-height: 40px;
        }

        .seat-element:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .seat-element.diamond:hover {
            transform: rotate(45deg) scale(1.1);
        }
        
        .seat-element.vip {
            background-color: #FBBF24; /* yellow-400 */
        }
        
        .seat-element.vip:hover {
            background-color: #F59E0B; /* yellow-500 */
        }
        
        .seat-element.regular {
            background-color: #60A5FA; /* blue-400 */
        }
        
        .seat-element.regular:hover {
            background-color: #3B82F6; /* blue-500 */
        }
        
        .seat-element.selected {
            outline: 2px solid #4F46E5; /* indigo-600 */
            outline-offset: 2px;
        }
        
        .seat-ghost {
            opacity: 0.5;
            background-color: #9CA3AF; /* gray-400 */
        }
        
        .shape-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .shape-btn:hover {
            border-color: #6366f1;
            background-color: #f3f4f6;
        }
        
        .shape-btn.active {
            border-color: #4f46e5;
            background-color: #e0e7ff;
        }
        
        .shape-rectangle {
            width: 20px;
            height: 12px;
            background-color: #6b7280;
            border-radius: 2px;
        }
        
        .shape-circle {
            width: 16px;
            height: 16px;
            background-color: #6b7280;
            border-radius: 50%;
        }
        
        .shape-diamond {
            width: 12px;
            height: 12px;
            background-color: #6b7280;
            transform: rotate(45deg);
        }
        
        .shape-square {
            width: 16px;
            height: 16px;
            background-color: #6b7280;
        }
</style>
@endpush
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Interactive Seat Layout Manager</h1>
            <p class="mt-2 text-sm text-gray-700">Buat dan kelola layout kursi dengan berbagai bentuk dan tipe</p>
        </div>

        <!-- Main Content -->
        <div class="flex gap-6">
            <!-- Sidebar -->
            <div class="w-80 bg-white rounded-lg shadow-sm border border-gray-200 p-6 h-fit">
                <div class="space-y-6">
                    <!-- Seat Shape Selection -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Bentuk Kursi</h4>
                        <div class="grid grid-cols-4 gap-2">
                            <div class="shape-btn active" data-shape="rectangle" title="Persegi Panjang">
                                <div class="shape-rectangle"></div>
                            </div>
                            <div class="shape-btn" data-shape="circle" title="Bulat">
                                <div class="shape-circle"></div>
                            </div>
                            <div class="shape-btn" data-shape="diamond" title="Diamond">
                                <div class="shape-diamond"></div>
                            </div>
                            <div class="shape-btn" data-shape="square" title="Persegi">
                                <div class="shape-square"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Type Selection -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Tipe Kursi</h4>
                        <div class="space-y-2">
                            <button type="button" 
                                    id="add-regular-btn"
                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-blue-50 hover:border-blue-300 tool-btn active"
                                    data-tool="regular">
                                <div class="w-4 h-4 bg-blue-400 rounded mr-3"></div>
                                Kursi Regular
                            </button>
                            <button type="button" 
                                    id="add-vip-btn"
                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-yellow-50 hover:border-yellow-300 tool-btn"
                                    data-tool="vip">
                                <div class="w-4 h-4 bg-yellow-400 rounded mr-3"></div>
                                Kursi VIP
                            </button>
                        </div>
                    </div>

                    <!-- Tools -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Tools</h4>
                        <div class="space-y-2">
                            <button type="button" 
                                    id="select-btn"
                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 hover:border-gray-400 tool-btn"
                                    data-tool="select">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.121 2.122"/>
                                </svg>
                                Pilih/Geser Kursi
                            </button>
                            <button type="button" 
                                    id="delete-btn"
                                    class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-red-50 hover:border-red-300 tool-btn"
                                    data-tool="delete">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Kursi
                            </button>
                        </div>
                    </div>

                    <!-- Background Upload -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Background</h4>
                        <input type="file" id="background-upload" accept="image/*" class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <button type="button" 
                                    id="create-grid-10x20"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                Grid 10x20 (200 kursi)
                            </button>
                            <button type="button" 
                                    id="create-arc-layout"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                Layout Arc (150 kursi)
                            </button>
                            <button type="button" 
                                    id="clear-all-seats"
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
                                    <input type="number" 
                                           id="regular_price"
                                           value="150000"
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
                                    <input type="number" 
                                           id="vip_price"
                                           value="300000"
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

                    <!-- Shape Statistics -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Bentuk Kursi</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Persegi Panjang:</span>
                                <span class="font-medium" id="rectangle-count">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bulat:</span>
                                <span class="font-medium" id="circle-count">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diamond:</span>
                                <span class="font-medium" id="diamond-count">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Persegi:</span>
                                <span class="font-medium" id="square-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Canvas Area -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Layout Designer</h4>
                        <p class="text-xs text-gray-500 mb-4">
                            Pilih bentuk dan tipe kursi, lalu klik di area canvas untuk menambahkan kursi. Drag untuk memindahkan, atau klik kursi untuk mengubah tipe.
                        </p>
                    </div>

                    <!-- Stage -->
                    <div class="mb-6">
                        <div class="bg-gray-800 text-white text-center py-4 px-6 rounded-lg text-sm font-medium max-w-md mx-auto">
                            PANGGUNG
                        </div>
                    </div>

                    <!-- Canvas -->
                    <div id="seat-canvas" class="relative bg-white border-2 border-gray-300 rounded-lg min-h-96 overflow-hidden" style="height: 600px; width: 100%;">
                        <!-- Seats will be dynamically added here -->
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4 text-xs text-gray-500">
                        <p><strong>Instruksi:</strong></p>
                        <ul class="list-disc list-inside space-y-1 mt-1">
                            <li>Pilih bentuk kursi dari 4 pilihan: persegi panjang, bulat, diamond, atau persegi</li>
                            <li>Pilih tipe kursi: Regular (biru) atau VIP (kuning)</li>
                            <li>Klik di area canvas untuk menambahkan kursi dengan bentuk dan tipe yang dipilih</li>
                            <li>Gunakan tool "Pilih/Geser" untuk memindahkan kursi</li>
                            <li>Klik kursi untuk mengubah tipe (Regular ↔ VIP)</li>
                            <li>Klik kanan pada kursi untuk mengubah bentuk</li>
                            <li>Gunakan tool "Hapus" untuk menghapus kursi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Context Menu -->
    <div id="context-menu" class="fixed bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50 hidden">
        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-action="change-type">
            Ubah Tipe (Regular/VIP)
        </button>
        <hr class="my-1">
        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-action="change-shape" data-shape="rectangle">
            Bentuk: Persegi Panjang
        </button>
        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-action="change-shape" data-shape="circle">
            Bentuk: Bulat
        </button>
        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-action="change-shape" data-shape="diamond">
            Bentuk: Diamond
        </button>
        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-action="change-shape" data-shape="square">
            Bentuk: Persegi
        </button>
        <hr class="my-1">
        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" data-action="delete">
            Hapus Kursi
        </button>
    </div>
</div>

<!-- Include interact.js -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.17/dist/interact.min.js"></script>
<script>
    document.getElementById('background-upload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('seat-canvas').style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            window.SeatManager = {
                seats: [],
                currentTool: 'regular',
                currentShape: 'rectangle',
                nextSeatId: 1,
                selectedSeat: null,
                contextMenuTarget: null,

                init() {
                    this.renderSeats();
                    this.updateStatistics();
                    this.initInteractions();
                    this.setupEventListeners();
                },

                setupEventListeners() {
                    // Background upload
                    document.getElementById('background-upload').addEventListener('change', (event) => {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                document.getElementById('seat-canvas').style.backgroundImage = `url(${e.target.result})`;
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    // Shape selection
                    document.querySelectorAll('.shape-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.shape-btn').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                            this.currentShape = btn.getAttribute('data-shape');
                        });
                    });

                    // Tool selection
                    document.getElementById('add-regular-btn').addEventListener('click', () => this.setTool('regular'));
                    document.getElementById('add-vip-btn').addEventListener('click', () => this.setTool('vip'));
                    document.getElementById('select-btn').addEventListener('click', () => this.setTool('select'));
                    document.getElementById('delete-btn').addEventListener('click', () => this.setTool('delete'));

                    // Quick actions
                    document.getElementById('create-grid-10x20').addEventListener('click', () => this.createGridLayout(10, 20));
                    document.getElementById('create-arc-layout').addEventListener('click', () => this.createArcLayout());
                    document.getElementById('clear-all-seats').addEventListener('click', () => this.clearAllSeats());

                    // Canvas click
                    document.getElementById('seat-canvas').addEventListener('click', (e) => this.handleCanvasClick(e));

                    // Price inputs
                    document.getElementById('regular_price').addEventListener('input', () => this.updateStatistics());
                    document.getElementById('vip_price').addEventListener('input', () => this.updateStatistics());

                    // Context menu
                    document.addEventListener('click', () => this.hideContextMenu());
                    document.querySelectorAll('#context-menu button').forEach(btn => {
                        btn.addEventListener('click', (e) => this.handleContextMenuAction(e));
                    });

                    // Hide context menu on scroll
                    document.addEventListener('scroll', () => this.hideContextMenu());
                },

                setTool(tool) {
                    this.currentTool = tool;
                    
                    // Update UI
                    document.querySelectorAll('.tool-btn').forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-100', 'border-blue-500', 'bg-yellow-100', 'border-yellow-500', 'bg-red-100', 'border-red-500', 'bg-gray-100', 'border-gray-500');
                    });
                    
                    const activeBtn = document.querySelector(`[data-tool="${tool}"]`);
                    if (activeBtn) {
                        activeBtn.classList.add('active');
                        
                        switch(tool) {
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
                        canvas.style.cursor = (tool === 'delete') ? 'crosshair' : 'default';
                    }
                    
                    this.deselectAllSeats();
                },

                deselectAllSeats() {
                    this.selectedSeat = null;
                    document.querySelectorAll('.seat-element.selected').forEach(seat => {
                        seat.classList.remove('selected');
                    });
                },

                renderSeats() {
                    const canvas = document.getElementById('seat-canvas');
                    if (!canvas) return;
                    
                    // Clear existing seats
                    canvas.querySelectorAll('.seat-element').forEach(el => el.remove());
                    
                    // Render each seat
                    this.seats.forEach(seat => {
                        this.createSeatElement(seat);
                    });
                },

                createSeatElement(seatData) {
                    const canvas = document.getElementById('seat-canvas');
                    if (!canvas) return;
                    
                    const seatEl = document.createElement('div');
                    seatEl.className = `seat-element ${seatData.type.toLowerCase()} ${seatData.shape}`;
                    seatEl.setAttribute('data-id', seatData.id);
                    seatEl.setAttribute('data-type', seatData.type);
                    seatEl.setAttribute('data-shape', seatData.shape);
                    seatEl.style.left = `${seatData.x}px`;
                    seatEl.style.top = `${seatData.y}px`;
                    
                    // Create text content
                    const seatNumber = seatData.number || seatData.id;
                    if (seatData.shape === 'diamond') {
                        const textSpan = document.createElement('span');
                        textSpan.className = 'seat-text';
                        textSpan.textContent = seatNumber;
                        seatEl.appendChild(textSpan);
                    } else {
                        seatEl.textContent = seatNumber;
                    }
                    
                    // Set title
                    const rowLabel = seatData.row || this.generateRowFromY(seatData.y);
                    seatEl.setAttribute('title', `${seatData.type} - ${seatData.shape} - ${rowLabel}${seatNumber}`);
                    
                    // Add event listeners
                    seatEl.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.handleSeatClick(seatData.id);
                    });

                    seatEl.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                        this.showContextMenu(e, seatData.id);
                    });
                    
                    canvas.appendChild(seatEl);
                    return seatEl;
                },

                generateRowFromY(y) {
                    const rowIndex = Math.floor(y / 30);
                    return String.fromCharCode(65 + Math.min(rowIndex, 25));
                },

                initInteractions() {
                    interact('.seat-element').draggable({
                        inertia: false,
                        modifiers: [
                            interact.modifiers.restrictRect({
                                restriction: 'parent',
                                endOnly: true
                            })
                        ],
                        autoScroll: true,
                        
                        listeners: {
                            start: (event) => {
                                if (this.currentTool !== 'select') {
                                    event.interaction.stop();
                                    return;
                                }
                                
                                const seatId = parseInt(event.target.getAttribute('data-id'));
                                this.deselectAllSeats();
                                this.selectedSeat = seatId;
                                event.target.classList.add('selected');
                            },
                            
                            move: (event) => {
                                const target = event.target;
                                const seatId = parseInt(target.getAttribute('data-id'));
                                const seatData = this.seats.find(seat => seat.id === seatId);
                                
                                const x = (parseFloat(target.style.left) || 0) + event.dx;
                                const y = (parseFloat(target.style.top) || 0) + event.dy;
                                
                                target.style.left = `${x}px`;
                                target.style.top = `${y}px`;
                                
                                if (seatData) {
                                    seatData.x = x;
                                    seatData.y = y;
                                    seatData.row = this.generateRowFromY(y);
                                    
                                    const seatNumber = seatData.number || seatData.id;
                                    target.setAttribute('title', `${seatData.type} - ${seatData.shape} - ${seatData.row}${seatNumber}`);
                                }
                            }
                        }
                    });
                },

                handleCanvasClick(e) {
                    if ((this.currentTool === 'regular' || this.currentTool === 'vip') && e.target.id === 'seat-canvas') {
                        const canvas = e.currentTarget;
                        const rect = canvas.getBoundingClientRect();
                        const x = e.clientX - rect.left - 20;
                        const y = e.clientY - rect.top - 20;
                        
                        this.addSeat(x, y, this.currentTool, this.currentShape);
                    }
                },

                handleSeatClick(seatId) {
                    const seat = this.seats.find(s => s.id === seatId);
                    if (!seat) return;
                    
                    if (this.currentTool === 'delete') {
                        this.deleteSeat(seatId);
                    } else if (this.currentTool === 'select') {
                        // Toggle between Regular and VIP
                        this.changeSeatType(seatId);
                    }
                },

                addSeat(x, y, type, shape) {
                    const seatType = type.charAt(0).toUpperCase() + type.slice(1).toLowerCase();
                    
                    const newSeat = {
                        id: this.nextSeatId++,
                        x: Math.max(0, x),
                        y: Math.max(0, y),
                        type: seatType,
                        shape: shape,
                        row: this.generateRowFromY(y),
                        number: this.nextSeatId - 1
                    };
                    
                    this.seats.push(newSeat);
                    this.createSeatElement(newSeat);
                    this.updateStatistics();
                },

                deleteSeat(seatId) {
                    this.seats = this.seats.filter(seat => seat.id !== seatId);
                    
                    const seatEl = document.querySelector(`.seat-element[data-id="${seatId}"]`);
                    if (seatEl) {
                        seatEl.remove();
                    }
                    
                    this.updateStatistics();
                },

                changeSeatType(seatId) {
                    const seat = this.seats.find(s => s.id === seatId);
                    if (!seat) return;
                    
                    seat.type = seat.type === 'VIP' ? 'Regular' : 'VIP';
                    
                    const seatEl = document.querySelector(`.seat-element[data-id="${seatId}"]`);
                    if (seatEl) {
                        seatEl.className = `seat-element ${seat.type.toLowerCase()} ${seat.shape}`;
                        seatEl.setAttribute('data-type', seat.type);
                        
                        const seatNumber = seat.number || seat.id;
                        seatEl.setAttribute('title', `${seat.type} - ${seat.shape} - ${seat.row}${seatNumber}`);
                    }
                    
                    this.updateStatistics();
                },

                changeSeatShape(seatId, newShape) {
                    const seat = this.seats.find(s => s.id === seatId);
                    if (!seat) return;
                    
                    seat.shape = newShape;
                    
                    const seatEl = document.querySelector(`.seat-element[data-id="${seatId}"]`);
                    if (seatEl) {
                        seatEl.className = `seat-element ${seat.type.toLowerCase()} ${newShape}`;
                        seatEl.setAttribute('data-shape', newShape);
                        
                        // Update content for diamond shape
                        const seatNumber = seat.number || seat.id;
                        if (newShape === 'diamond') {
                            seatEl.innerHTML = `<span class="seat-text">${seatNumber}</span>`;
                        } else {
                            seatEl.textContent = seatNumber;
                        }
                        
                        seatEl.setAttribute('title', `${seat.type} - ${newShape} - ${seat.row}${seatNumber}`);
                    }
                    
                    this.updateStatistics();
                },

                showContextMenu(event, seatId) {
                    const contextMenu = document.getElementById('context-menu');
                    this.contextMenuTarget = seatId;
                    
                    contextMenu.style.left = `${event.pageX}px`;
                    contextMenu.style.top = `${event.pageY}px`;
                    contextMenu.classList.remove('hidden');
                },

                hideContextMenu() {
                    const contextMenu = document.getElementById('context-menu');
                    contextMenu.classList.add('hidden');
                    this.contextMenuTarget = null;
                },

                handleContextMenuAction(event) {
                    event.stopPropagation();
                    
                    if (!this.contextMenuTarget) return;
                    
                    const action = event.target.getAttribute('data-action');
                    
                    switch (action) {
                        case 'change-type':
                            this.changeSeatType(this.contextMenuTarget);
                            break;
                        case 'change-shape':
                            const newShape = event.target.getAttribute('data-shape');
                            this.changeSeatShape(this.contextMenuTarget, newShape);
                            break;
                        case 'delete':
                            this.deleteSeat(this.contextMenuTarget);
                            break;
                    }
                    
                    this.hideContextMenu();
                },

                createGridLayout(rows, cols) {
                    if (!confirm(`Buat layout grid ${rows}x${cols}? Ini akan menghapus kursi yang ada.`)) return;
                    
                    this.seats = [];
                    
                    const seatSize = 30;
                    const spacing = 10;
                    const startX = 50;
                    const startY = 80;
                    
                    let seatId = 1;
                    
                    for (let row = 0; row < rows; row++) {
                        for (let col = 0; col < cols; col++) {
                            const x = startX + (col * (seatSize + spacing));
                            const y = startY + (row * (seatSize + spacing));
                            
                            // Alternate shapes for variety
                            const shapes = ['rectangle', 'circle', 'square'];
                            const shape = shapes[col % 3];
                            
                            // VIP for first 3 rows
                            const type = row < 3 ? 'VIP' : 'Regular';
                            
                            this.seats.push({
                                id: seatId++,
                                x: x,
                                y: y,
                                type: type,
                                shape: shape,
                                row: String.fromCharCode(65 + row),
                                number: col + 1
                            });
                        }
                    }
                    
                    this.nextSeatId = seatId;
                    this.renderSeats();
                    this.updateStatistics();
                    this.initInteractions();
                },

                createArcLayout() {
                    if (!confirm('Buat layout arc dengan 150 kursi? Ini akan menghapus kursi yang ada.')) return;
                    
                    this.seats = [];
                    
                    const centerX = 400;
                    const startY = 100;
                    const rows = 10;
                    const rowSpacing = 35;
                    
                    let seatId = 1;
                    
                    for (let row = 0; row < rows; row++) {
                        const seatsInRow = 10 + (row * 2);
                        const arcWidth = (seatsInRow * 30) * 0.8;
                        
                        for (let seat = 0; seat < seatsInRow; seat++) {
                            const angle = (seat / (seatsInRow - 1)) * Math.PI;
                            const x = centerX - (arcWidth / 2) + (arcWidth * (seat / (seatsInRow - 1)));
                            const y = startY + (row * rowSpacing) + (Math.sin(angle) * (row * 2));
                            
                            // Different shapes for different sections
                            let shape = 'circle';
                            if (row < 3) shape = 'diamond';
                            else if (row > 7) shape = 'square';
                            else if (seat % 2 === 0) shape = 'rectangle';
                            
                            const seatType = row < 3 ? 'VIP' : 'Regular';
                            
                            this.seats.push({
                                id: seatId++,
                                x: x,
                                y: y,
                                type: seatType,
                                shape: shape,
                                row: String.fromCharCode(65 + row),
                                number: seat + 1
                            });
                        }
                    }
                    
                    this.nextSeatId = seatId;
                    this.renderSeats();
                    this.updateStatistics();
                    this.initInteractions();
                },

                clearAllSeats() {
                    if (!confirm('Hapus semua kursi? Tindakan ini tidak dapat dibatalkan.')) return;
                    
                    this.seats = [];
                    this.nextSeatId = 1;
                    this.renderSeats();
                    this.updateStatistics();
                },

                updateStatistics() {
                    const totalSeats = this.seats.length;
                    const regularSeats = this.seats.filter(seat => seat.type === 'Regular').length;
                    const vipSeats = this.seats.filter(seat => seat.type === 'VIP').length;
                    
                    // Shape counts
                    const rectangleCount = this.seats.filter(seat => seat.shape === 'rectangle').length;
                    const circleCount = this.seats.filter(seat => seat.shape === 'circle').length;
                    const diamondCount = this.seats.filter(seat => seat.shape === 'diamond').length;
                    const squareCount = this.seats.filter(seat => seat.shape === 'square').length;
                    
                    const regularPrice = parseInt(document.getElementById('regular_price').value) || 150000;
                    const vipPrice = parseInt(document.getElementById('vip_price').value) || 300000;
                    
                    const estimatedRevenue = (regularSeats * regularPrice) + (vipSeats * vipPrice);
                    
                    // Update main statistics
                    document.getElementById('total-seats').textContent = totalSeats;
                    document.getElementById('regular-seats').textContent = regularSeats;
                    document.getElementById('vip-seats').textContent = vipSeats;
                    document.getElementById('estimated-revenue').textContent = 'Rp ' + estimatedRevenue.toLocaleString('id-ID');
                    
                    // Update shape statistics
                    document.getElementById('rectangle-count').textContent = rectangleCount;
                    document.getElementById('circle-count').textContent = circleCount;
                    document.getElementById('diamond-count').textContent = diamondCount;
                    document.getElementById('square-count').textContent = squareCount;
                },

                exportLayout() {
                    return {
                        seats: this.seats,
                        statistics: {
                            total: this.seats.length,
                            regular: this.seats.filter(s => s.type === 'Regular').length,
                            vip: this.seats.filter(s => s.type === 'VIP').length,
                            shapes: {
                                rectangle: this.seats.filter(s => s.shape === 'rectangle').length,
                                circle: this.seats.filter(s => s.shape === 'circle').length,
                                diamond: this.seats.filter(s => s.shape === 'diamond').length,
                                square: this.seats.filter(s => s.shape === 'square').length
                            }
                        }
                    };
                },

                importLayout(layoutData) {
                    if (layoutData && layoutData.seats) {
                        this.seats = layoutData.seats;
                        this.nextSeatId = Math.max(...this.seats.map(seat => seat.id)) + 1;
                        this.renderSeats();
                        this.updateStatistics();
                        this.initInteractions();
                    }
                }
            };

            // Initialize the seat manager
            SeatManager.init();

            // Add export/import buttons to the DOM (for testing)
            const quickActionsDiv = document.querySelector('div:has(#clear-all-seats)').parentElement;
            
            const exportBtn = document.createElement('button');
            exportBtn.textContent = 'Export Layout';
            exportBtn.className = 'w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 mt-2';
            exportBtn.addEventListener('click', () => {
                const layout = SeatManager.exportLayout();
                console.log('Layout Data:', layout);
                
                // Create download link
                const dataStr = JSON.stringify(layout, null, 2);
                const dataBlob = new Blob([dataStr], {type: 'application/json'});
                const url = URL.createObjectURL(dataBlob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'seat-layout.json';
                link.click();
                URL.revokeObjectURL(url);
            });
            
            const importBtn = document.createElement('button');
            importBtn.textContent = 'Import Layout';
            importBtn.className = 'w-full px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 mt-1';
            importBtn.addEventListener('click', () => {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.json';
                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            try {
                                const layout = JSON.parse(e.target.result);
                                SeatManager.importLayout(layout);
                                alert('Layout berhasil diimport!');
                            } catch (error) {
                                alert('File tidak valid!');
                            }
                        };
                        reader.readAsText(file);
                    }
                });
                input.click();
            });
            
            quickActionsDiv.appendChild(exportBtn);
            quickActionsDiv.appendChild(importBtn);
        });
    </script>
@endpush