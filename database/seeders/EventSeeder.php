<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        DB::table('events')->insert([
            [
                'title' => 'Rock Festival',
                'description' => 'A great music event with top artists.',
                'date' => Carbon::now()->addDays(10),
                'location' => 'New York City',
                'max_participants' => 500,
                'category_id' => 1,
                'created_by' => 2,
                'image' => 'rock-festival.jpg'
            ],
            [
                'title' => 'AI Conference 2024',
                'description' => 'Exploring AI innovations and future trends.',
                'date' => Carbon::now()->addDays(30),
                'location' => 'San Francisco',
                'max_participants' => 300,
                'category_id' => 2,
                'created_by' => 2,
                'image' => 'ai-conference.jpg'
            ]
        ]);
    }
}
