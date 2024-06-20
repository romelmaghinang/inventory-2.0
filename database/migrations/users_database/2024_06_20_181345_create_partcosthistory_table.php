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
        Schema::create('partcosthistory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('avgCost', 28, 9)->nullable();
            $table->dateTime('dateCaptured');
            $table->decimal('nextCost', 28, 9)->nullable();
            $table->integer('partId')->unique('u_partid');
            $table->decimal('quantity', 28, 9)->nullable();
            $table->decimal('stdCost', 28, 9)->nullable();
            $table->decimal('totalCost', 28, 9)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partcosthistory');
    }
};
