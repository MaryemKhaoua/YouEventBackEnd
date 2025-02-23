<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'organizer'],
            ['name' => 'user']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
