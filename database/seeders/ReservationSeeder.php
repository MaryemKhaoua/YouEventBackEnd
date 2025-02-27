<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        DB::table('reservations')->insert([
            ['user_id' => 3, 'event_id' => 1, 'ticket_id' => 1, 'quantity' => 2],
            ['user_id' => 3, 'event_id' => 2, 'ticket_id' => 2, 'quantity' => 1],
        ]);
    }
}
