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
        Schema::create('picktype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('picktype')->insert(
            [
                ['id' => 30, 'name' => 'Move'],
                ['id' => 10, 'name' => 'Pick'],
                ['id' => 20, 'name' => 'Putaway'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picktype');
    }
};
