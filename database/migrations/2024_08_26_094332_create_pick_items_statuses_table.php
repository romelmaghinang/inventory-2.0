<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pickitemstatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('pickitemstatus')->insert([
            ['id' => 30, 'name' => 'Comitted'],
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 11, 'name' => 'Entered New'],
            ['id' => 40, 'name' => 'Finished'],
            ['id' => 6, 'name' => 'Hold'],
            ['id' => 5, 'name' => 'Short'],
            ['id' => 20, 'name' => 'Started'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickitemstatus');
    }
};
