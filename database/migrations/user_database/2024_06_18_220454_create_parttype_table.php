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
        Schema::create('parttype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('parttype')->insert(
            [
                ['id' => 50, 'name' => 'Capital Equipment'],
                ['id' => 40, 'name' => 'Internal Use'],
                ['id' => 10, 'name' => 'Inventory'],
                ['id' => 21, 'name' => 'Labor'],
                ['id' => 80, 'name' => 'Misc'],
                ['id' => 30, 'name' => 'Non-inventory'],
                ['id' => 22, 'name' => 'Overhead'],
                ['id' => 20, 'name' => 'Service'],
                ['id' => 60, 'name' => 'Shipping'],
                ['id' => 70, 'name' => 'Tax'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_types');
    }
};
