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
        Schema::create('customerstatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('customerstatus')->insert(
            [
                ['id' => 50,'name' => 'Hold All'],
                ['id' => 30,'name' => 'Hold Sales'],
                ['id' => 40,'name' => 'Hold Shipment'],
                ['id' => 10,'name' => 'Normal'],
                ['id' => 20,'name' => 'Preferred'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_statuses');
    }
};
