<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Logout;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->group(function () {
        // Route::get('/events', App\Livewire\App\Event\Index::class)->name('events.index');
        // Route::get('/events/create', App\Livewire\App\Event\Create::class)->name('events.create');
        // Route::get('/events/{id}', App\Livewire\App\Event\Edit::class)->name('events.edit');

        // Route::get('/tickets', App\Livewire\App\Ticket\Index::class)->name('ticket.index');
        // Route::get('/ticket/create', App\Livewire\App\Ticket\Create::class)->name('ticket.create');
        // Route::get('ticket/{id}', App\Livewire\App\Ticket\Edit::class)->name('ticket.edit');
        Route::get('/event', App\Livewire\Admin\EventManagement::class)->name('event.management');
        Route::get('/event/index', App\Livewire\Admin\Event\Index::class)->name('event.index');
        Route::get('/event/create', App\Livewire\Admin\Event\Create::class)->name('event.create');
        Route::get('/event/edit/{id}', App\Livewire\Admin\Event\Edit::class)->name('event.edit');
    });

});
    

Route::get('/login', App\Livewire\Auth\Login::class)->name('login')->middleware('guest');
Route::get('/logout', [Logout::class, 'logout'])->name('logout');

// Route::get('/event/list', [App\Livewire\Admin\Eventl::class)->name('event.management');