<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>
    {{-- <div class="flex gap-10 items-center justify-center h-24 bg-gray-100">
        <a href="{{route('events.index')}}">Event</a>
        <a href="{{route('ticket.index')}}">Ticket</a>
    </div> --}}
    <div>
        @if (auth()->check())
            <div class="p-4 bg-white rounded shadow">
                <h2 class="text-lg font-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-sm text-gray-600">Email: {{ auth()->user()->email }}</p>
                <p class="text-sm text-gray-600">Role: {{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
        @endif
    </div>
    <a 
        href="{{route('logout')}}"
        {{-- wire:click.prevent="$emit('openModal', 'auth.logout')" --}}
        class="flex space-x-2 group"
    >
        <div class="w-1.5 h-6 rounded-md shrink-0 bg-transparent group-hover:bg-brand transition"></div>
        <div class="flex w-full pr-6 space-x-3">
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="w-6 h-6 text-black transition shrink-0 group-hover:text-brand" 
                viewBox="0 0 24 24" 
                stroke-width="2" 
                stroke="currentColor" 
                fill="none" 
                stroke-linecap="round" 
                stroke-linejoin="round"
            >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M13 12v.01"></path>
                <path d="M3 21h18"></path>
                <path d="M5 21v-16a2 2 0 0 1 2 -2h7.5m2.5 10.5v7.5"></path>
                <path d="M14 7h7m-3 -3l3 3l-3 3"></path>
            </svg>
            <span class="text-lg text-black capitalize truncate transition group-hover:text-brand">
                keluar
            </span>
        </div>
    </a>
</body>
</html>