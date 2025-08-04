<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\SeatLayout;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('user_type', 'admin')->first();

        // Create sample event
        $event = Event::create([
            'event_name' => 'Laksamana Muda Grand Opening',
            'event_description' => 'Grand opening event with live music and entertainment',
            'venue_name' => 'Laksamana Muda Venue',
            'venue_address' => 'Jl. Laksamana Muda No. 123, Jakarta',
            'event_date' => now()->addDays(30),
            'start_time' => '19:00:00',
            'end_time' => '23:00:00',
            'event_image' => 'events/grand-opening.jpg',
            'is_active' => true,
            'created_by' => $admin->user_id,
        ]);

        // Create seat layout
        $layout = SeatLayout::create([
            'event_id' => $event->event_id,
            'layout_name' => 'Main Hall Layout',
            'layout_config' => [
                'rows' => 10,
                'columns' => 20,
                'vip_rows' => [1, 2, 3],
                'stage_position' => 'front'
            ],
        ]);

        // Create seats
        $seatNumber = 1;
        for ($row = 1; $row <= 10; $row++) {
            for ($col = 1; $col <= 20; $col++) {
                $isVip = in_array($row, [1, 2, 3]);
                
                Seat::create([
                    'layout_id' => $layout->layout_id,
                    'seat_number' => str_pad($seatNumber, 3, '0', STR_PAD_LEFT),
                    'seat_row' => chr(65 + $row - 1), // A, B, C, etc.
                    'seat_type' => $isVip ? 'VIP' : 'Regular',
                    'seat_price' => $isVip ? 300000 : 150000,
                    'is_available' => true,
                ]);
                
                $seatNumber++;
            }
        }
    }
}
