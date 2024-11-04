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
        Schema::create('shipstatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });


        DB::table('shipstatus')->insert(
            [
                'id' => 40,
                'name' => 'Cancelled',
                'id' => 10,
                'name' => 'Entered',
                'id' => 20,
                'name' => 'Packed',
                'id' => 30,
                'name' => 'Shipped',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_statuses');
    }
};
