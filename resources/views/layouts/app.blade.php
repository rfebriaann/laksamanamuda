<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" /> --}}
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @vite('resources/css/app.css')
        @livewireStyles
        @stack('styles')
    </head>
    <body class="">
        {{-- <x-side-menu /> --}}
        <div class="sm:ml-64 md:ml-0 bg-gray-50 m-6 rounded-2xl">
            {{$slot}}
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.17/dist/interact.min.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script> --}}

        @livewireScripts
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
        {{-- <script src="https://cdn.datatables.net/v/bs4/dt-1.13.6/datatables.min.js"></script> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>


</html>
