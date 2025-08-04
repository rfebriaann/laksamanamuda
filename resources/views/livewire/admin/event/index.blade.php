<div>
    <h1 class="text-2xl font-bold mb-4">Event Management</h1>

    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Search events..." class="border rounded p-2">
    </div>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2">Event</th>
                <th class="border border-gray-300 p-2">Venue</th>
                <th class="border border-gray-300 p-2">Date</th>
                <th class="border border-gray-300 p-2">Status</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
          {{-- @dd($events) --}}
            @foreach ($events as $event)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($event->event_image)
                                    <img class="h-12 w-12 rounded-lg object-cover mr-4" src="{{ Storage::url($event->event_image) }}" alt="{{ $event->event_name }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $event->event_name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->event_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                    {{-- <td class="border border-gray-300 p-2">{{ $event->event_name }}</td> --}}
                    <td class="border border-gray-300 p-2">{{ $event->venue_name }}</td>
                    <td class="border border-gray-300 p-2">
                    <div class="text-sm text-gray-900">{{ $event->event_date->format('d M Y') }}</div>
                    <div class="text-sm text-gray-500">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</div>
                    </td>
                    <td class="border border-gray-300 p-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                    </td>
                    <td class="border border-gray-300 p-2">
                        <a href="{{ route('event.edit', $event->event_id) }}" class="text-blue-500">Edit</a>
                        <button wire:click="confirmDeleteEvent({{ $event->event_id }})" class="text-red-500">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $events->links() }}
    </div>

    <div class="mt-4">
        <a href="{{ route('event.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create New Event</a>
    </div>

    @if ($confirmingEventDeletion)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-4 rounded">
                <h2 class="text-lg font-bold">Confirm Deletion</h2>
                <p>Are you sure you want to delete this event?</p>
                <div class="mt-4">
                    <button wire:click="deleteEvent" class="bg-red-500 text-white px-4 py-2 rounded">Yes, Delete</button>
                    <button wire:click="$set('confirmingEventDeletion', false)" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>