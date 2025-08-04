@props(['show' => false, 'maxWidth' => '2xl'])

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md', 
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth];
@endphp

<div 
    @if($attributes->wire('model')->value())
        wire:model="{{ $attributes->wire('model')->value() }}"
    @endif
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-lg shadow-xl {{ $maxWidth }} w-full">
            {{ $slot }}
        </div>
    </div>
</div>