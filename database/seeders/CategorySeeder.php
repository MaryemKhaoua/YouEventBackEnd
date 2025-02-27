<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Music', 'description' => 'Music concerts and festivals'],
            ['name' => 'Tech', 'description' => 'Tech conferences and hackathons'],
            ['name' => 'Sports', 'description' => 'Sporting events and tournaments'],
        ]);
    }
}
