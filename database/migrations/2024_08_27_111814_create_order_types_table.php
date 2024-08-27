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
        Schema::create('ordertype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('ordertype')->insert([
            ['id' => 1, 'name' => 'None'],
            ['id' => 10, 'name' => 'PO'],
            ['id' => 20, 'name' => 'SO'],
            ['id' => 30, 'name' => 'TO'],
            ['id' => 40, 'name' => 'WO'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordertype');
    }
};