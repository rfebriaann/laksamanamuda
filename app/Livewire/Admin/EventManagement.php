<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EventManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $event_name = '';
    public $event_description = '';
    public $venue_name = '';
    public $venue_address = '';
    public $event_date = '';
    public $start_time = '';
    public $end_time = '';
    public $event_image;
    public $search = '';
    public $showModal = false;
    public $editingEventId = null;
    public $confirmingEventDeletion = false;
    public $eventToDelete = null;

    protected $rules = [
        'event_name' => 'required|string|max:255',
        'event_description' => 'nullable|string',
        'venue_name' => 'required|string|max:255',
        'venue_address' => 'required|string',
        'event_date' => 'required|date|after:today',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'event_image' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'event_name.required' => 'Nama event harus diisi.',
        'venue_name.required' => 'Nama venue harus diisi.',
        'venue_address.required' => 'Alamat venue harus diisi.',
        'event_date.required' => 'Tanggal event harus diisi.',
        'event_date.after' => 'Tanggal event harus setelah hari ini.',
        'start_time.required' => 'Waktu mulai harus diisi.',
        'end_time.required' => 'Waktu selesai harus diisi.',
        'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        'event_image.image' => 'File harus berupa gambar.',
        'event_image.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount()
    {
        // Memastikan pengguna memiliki peran yang sesuai
        // if (!auth()->user() || !auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized action.');
        // }
    }

    public function render()
    {
        $events = Event::query()
            ->when($this->search, fn($query) => $query->where('event_name', 'like', '%' . $this->search . '%'))
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.event-management', compact('events'));
    }

    public function createEvent()
    {
        // Memastikan pengguna memiliki izin untuk membuat event
        // if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('superadmin')) {
        //     abort(403, 'Unauthorized action.');
        // }

        $this->resetForm();
        $this->showModal = true;
    }

    public function editEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Memastikan pengguna memiliki izin untuk mengedit event
        // if (!auth()->user()->can('update events')) {
        //     abort(403, 'Unauthorized action.');
        // }

        $this->editingEventId = $eventId;
        $this->event_name = $event->event_name;
        $this->event_description = $event->event_description;
        $this->venue_name = $event->venue_name;
        $this->venue_address = $event->venue_address;
        $this->event_date = $event->event_date->format('Y-m-d');
        $this->start_time = $event->start_time->format('H:i');
        $this->end_time = $event->end_time->format('H:i');
        
        $this->showModal = true;
    }

    public function saveEvent()
    {
        // Update validation rules for editing
        if ($this->editingEventId) {
            $this->rules['event_date'] = 'required|date'; // Remove 'after:today' for editing
        }

        $this->validate();

        $eventData = [
            'event_name' => $this->event_name,
            'event_description' => $this->event_description,
            'venue_name' => $this->venue_name,
            'venue_address' => $this->venue_address,
            'event_date' => $this->event_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];

        if ($this->event_image) {
            $eventData['event_image'] = $this->event_image->store('events', 'public');
        }

        if ($this->editingEventId) {
            $event = Event::findOrFail($this->editingEventId);
            
            // Delete old image if new image is uploaded
            if ($this->event_image && $event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }
            
            $event->update($eventData);
            session()->flash('message', 'Event berhasil diperbarui!');
        } else {
            // Memastikan pengguna memiliki izin untuk membuat event
            // if (!auth()->user()->can('create events')) {
            //     abort(403, 'Unauthorized action.');
            // }
            
            $eventData['created_by'] = auth()->id();
            Event::create($eventData);
            session()->flash('message', 'Event berhasil dibuat!');
        }

        $this->closeModal();
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
            
            // Delete event image if exists
            if ($event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }
            
            $event->delete();
            session()->flash('message', 'Event berhasil dihapus!');
        }

        $this->confirmingEventDeletion = false;
        $this->eventToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingEventId = null;
        $this->event_name = '';
        $this->event_description = '';
        $this->venue_name = '';
        $this->venue_address = '';
        $this->event_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->event_image = null;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}