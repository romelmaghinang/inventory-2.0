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
        Schema::create('partcost', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('avgCost', 28, 9);
            $table->dateTime('dateCreated');
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('partId')->unique('u_partid');
            $table->decimal('qty', 28, 9);
            $table->decimal('totalCost', 28, 9);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partcost');
    }
};
