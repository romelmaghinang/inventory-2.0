<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('soitemtype', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 30)->unique('u_name');
        });

        DB::table('soitemtype')->insert([
            ['id' => 50, 'name' => 'Assoc. Price'],
            ['id' => 20, 'name' => 'Credit Return'],
            ['id' => 31, 'name' => 'Discount Amount'],
            ['id' => 30, 'name' => 'Discount Percentage'],
            ['id' => 12, 'name' => 'Drop Ship'],
            ['id' => 80, 'name' => 'Kit'],
            ['id' => 21, 'name' => 'Misc. Credit'],
            ['id' => 11, 'name' => 'Misc. Sale'],
            ['id' => 90, 'name' => 'Note'],
            ['id' => 10, 'name' => 'Sale'],
            ['id' => 60, 'name' => 'Shipping'],
            ['id' => 40, 'name' => 'Subtotal'],
            ['id' => 70, 'name' => 'Tax'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soitemtype');
    }
};
