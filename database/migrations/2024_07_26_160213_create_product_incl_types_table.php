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
        Schema::create('productincltype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('productincltype')->insert(
            [
                ['id' => 1, 'name' => 'All'],
                ['id' => 2, 'name' => 'Part Category'],
                ['id'=> 3, 'name'=> 'Product'],
                ['id'=> 4, 'name'=> 'Product Tree'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_incl_types');
    }
};
