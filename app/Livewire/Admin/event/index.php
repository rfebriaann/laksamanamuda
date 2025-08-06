<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingEventDeletion = false;
    public $eventToDelete = null;

    public function render()
    {
        $events = Event::query()
            ->when($this->search, fn($query) => $query->where('event_name', 'like', '%' . $this->search . '%'))
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.event.index', compact('events'));
    }

    public function confirmDeleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Memastikan pengguna memiliki izin untuk menghapus event
        // if (!auth()->user()->can('delete events')) {
        //     abort(403, 'Unauthorized action.');
        // }

        $this->eventToDelete = $eventId;
        $this->confirmingEventDeletion = true;
    }

    public function deleteEvent()
    {
        if ($this->eventToDelete) {
            $event = Event::findOrFail($this->eventToDelete);
            $event->delete();
            session()->flash('message', 'Event berhasil dihapus!');
        }

        $this->confirmingEventDeletion = false;
        $this->eventToDelete = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
