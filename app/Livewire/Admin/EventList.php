<?php

namespace App\Livewire\User;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'event_date';
    public $sortDirection = 'asc';

    protected $queryString = ['search'];

    public function render()
    {
        $events = Event::query()
            ->where('is_active', true)
            ->where('event_date', '>=', now()->toDateString())
            ->when($this->search, fn($query) => 
                $query->where('event_name', 'like', '%' . $this->search . '%')
                    ->orWhere('venue_name', 'like', '%' . $this->search . '%')
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(9);

        return view('livewire.user.event-list', compact('events'));
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}