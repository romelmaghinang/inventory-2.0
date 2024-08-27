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
        Schema::create('pickitemtype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('pickitemtype')->insert([
            ['id' => 30,'name'=> 'BTO'],
            ['id' => 10,'name'=> 'Normal'],
            ['id' => 20,'name'=> 'PFL'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick_item_types');
    }
};
