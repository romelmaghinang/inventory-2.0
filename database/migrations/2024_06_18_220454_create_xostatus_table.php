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
        Schema::create('xostatus', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 30)->unique('u_name');
        });

        DB::table('xostatus')->insert([
            ['id' => 80, 'name' => 'Closed Short'],
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 70, 'name' => 'Fulfilled'],
            ['id' => 20, 'name' => 'Issued'],
            ['id' => 40, 'name' => 'Partial'],
            ['id' => 50, 'name' => 'Picked'],
            ['id' => 30, 'name' => 'Picking'],
            ['id' => 15, 'name' => 'Request'],
            ['id' => 60, 'name' => 'Shipped'],
            ['id' => 90, 'name' => 'Void'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xostatus');
    }
};
