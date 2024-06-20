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
        Schema::create('uomconversion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description', 256);
            $table->double('factor')->nullable();
            $table->integer('fromUomId')->nullable()->index('performance');
            $table->double('multiply')->nullable();
            $table->integer('toUomId')->nullable()->unique('u_touomid');

            $table->unique(['fromUomId'], 'u_fromuomid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uomconversion');
    }
};
