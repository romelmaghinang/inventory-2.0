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
        Schema::create('locationtype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
        });

        DB::table('locationtype')->insert(
            [
                ['name' => 'Stock'],
                ['name' => 'Shipping'],
                ['name' => 'Receiving'],
                ['name' => 'Vendor'],
                ['name' => 'Inspection'],
                ['name' => 'Locked'],
                ['name' => 'Store Front'],
                ['name' => 'Manufacturing'],
                ['name' => 'Picking'],
                ['name' => 'In Transit'],
                ['name' => 'Consignment'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_types');
    }
};
