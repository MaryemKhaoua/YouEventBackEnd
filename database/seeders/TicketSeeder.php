<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    public function run()
    {
        DB::table('tickets')->insert([
            ['event_id' => 1, 'price' => 50.00, 'quantity' => 200],
            ['event_id' => 2, 'price' => 100.00, 'quantity' => 150],
        ]);
    }
}
