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
        Schema::create('poitemtype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15)->unique();
        });

        DB::table('poitemtype')->insert(
            [
                ['id' => 10, 'name' => 'Purchase'],
                ['id' => 11, 'name' => 'Misc. Purchase'],
                ['id' => 20, 'name' => 'Credit Return'],
                ['id' => 21, 'name' => 'Misc. Credit'],
                ['id' => 30, 'name' => 'Out Sourced'],
                ['id' => 40, 'name' => 'Shipping'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_item_types');
    }
};
