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
        Schema::create('quicklistitem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('productId');
            $table->integer('qListId');
            $table->decimal('quantity', 28, 9)->nullable();

            $table->index(['qListId', 'productId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quicklistitem');
    }
};
