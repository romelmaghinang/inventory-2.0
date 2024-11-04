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
        Schema::create('uomtype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15)->unique();
        });

        DB::table('uomtype')->insert(
            [
                ['name' => 'Count'],
                ['name' => 'Weight'],
                ['name' => 'Length'],
                ['name' => 'Area'],
                ['name' => 'Volume'],
                ['name' => 'Time'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_of_measure_types');
    }
};
