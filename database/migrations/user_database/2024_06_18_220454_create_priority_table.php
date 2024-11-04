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
        Schema::create('priority', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
        });

        DB::table('priority')->insert(
            [
                ['id' => 10, 'name' => 'Highest'],
                ['id' => 20, 'name' => 'High'],
                ['id' => 30, 'name' => 'Normal'],
                ['id' => 40, 'name' => 'Low'],
                ['id' => 50, 'name' => 'Lowest'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
