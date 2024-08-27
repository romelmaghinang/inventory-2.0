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
        Schema::create('pickstatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('pickstatus')->insert([
            ['id' => 30, 'name' => 'Committed'],
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 40, 'name' => 'Finished'],
            ['id' => 20, 'name' => 'Started'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick_statuses');
    }
};
