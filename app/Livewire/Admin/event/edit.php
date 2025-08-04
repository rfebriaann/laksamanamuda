<?php

namespace App\Livewire\Admin\event;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $event_name = '';
    public $event_description = '';
    public $venue_name = '';
    public $venue_address = '';
    public $event_date = '';
    public $start_time = '';
    public $end_time = '';
    public $event_image;
    public $editingEventId;

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

    public function mount($id)
    {
        $event = Event::findOrFail($id);
        
        // Memastikan pengguna memiliki izin untuk mengedit event
//         if (!auth()->user()->can('update events')) {
//             abort(403, 'Unauthorized action.');
//         }

        $this->editingEventId = $id;
        $this->event_name = $event->event_name;
        $this->event_description = $event->event_description;
        $this->venue_name = $event->venue_name;
        $this->venue_address = $event->venue_address;
        $this->event_date = $event->event_date->format('Y-m-d');
        $this->start_time = $event->start_time->format('H:i');
        $this->end_time = $event->end_time->format('H:i');
    }

    public function saveEvent()
    {
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

        $event = Event::findOrFail($this->editingEventId);
        $event->update($eventData);
        session()->flash('message', 'Event berhasil diperbarui!');

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

    public function render()
    {
        return view('livewire.admin.event.edit');
    }
}
