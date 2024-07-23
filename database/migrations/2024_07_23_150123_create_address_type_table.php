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
        Schema::create('addresstype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15)->unique('name');
        });

        DB::table('addresstype')->insert([
            ['name' => 'Bill To'],
            ['name' => 'Home'],
            ['name' => 'Main Office'],
            ['name' => 'Remit To'],
            ['name' => 'Ship To'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresstype');
    }
};
