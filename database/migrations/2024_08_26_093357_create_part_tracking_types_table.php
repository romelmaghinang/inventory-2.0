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
        Schema::create('parttrackingtype', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
        });

        DB::table('parttrackingtype')->insert([
            ['id' => 80, 'name' => 'Checkbox'],
            ['id' => 70, 'name' => 'Count'],
            ['id' => 20, 'name' => 'Date'],
            ['id' => 30, 'name' => 'Expiration Date'],
            ['id' => 50, 'name' => 'Money'],
            ['id' => 60, 'name' => 'Quantity'],
            ['id' => 40, 'name' => 'Serial Number'],
            ['id' => 10, 'name' => 'Text'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parttrackingtype');
    }
};
