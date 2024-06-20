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
        Schema::create('producttotree', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('productId')->unique('u_roductid');
            $table->integer('productTreeId')->unique('u_producttreeid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producttotree');
    }
};
