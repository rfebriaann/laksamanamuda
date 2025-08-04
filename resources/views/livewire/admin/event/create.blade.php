<div>
    <h1 class="text-2xl font-bold mb-4">Create New Event</h1>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveEvent">
        <div class="mb-4">
            <label class="block mb-1">Event Name</label>
            <input type="text" wire:model="event_name" class="border rounded p-2 w-full" required>
            @error('event_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Event Description</label>
            <textarea wire:model="event_description" class="border rounded p-2 w-full"></textarea>
            @error('event_description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Venue Name</label>
            <input type="text" wire:model="venue_name" class="border rounded p-2 w-full" required>
            @error('venue_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Venue Address</label>
            <input type="text" wire:model="venue_address" class="border rounded p-2 w-full" required>
            @error('venue_address') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Event Date</label>
            <input type="date" wire:model="event_date" class="border rounded p-2 w-full" required>
            @error('event_date') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Start Time</label>
            <input type="time" wire:model="start_time" class="border rounded p-2 w-full" required>
            @error('start_time') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">End Time</label>
            <input type="time" wire:model="end_time" class="border rounded p-2 w-full" required>
            @error('end_time') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Event Image</label>
            <input type="file" wire:model="event_image" class="border rounded p-2 w-full">
            @error('event_image') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Event</button>
    </form>

    <div class="mt-4">
        <a href="{{ route('event.index') }}" class="text-blue-500">Back to Event List</a>
    </div>
</div>