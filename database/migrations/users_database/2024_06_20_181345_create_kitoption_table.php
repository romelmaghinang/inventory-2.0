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
        Schema::create('kitoption', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultFlag');
            $table->integer('kitItemId');
            $table->decimal('priceAdjustment', 28, 9)->nullable();
            $table->integer('productId');

            $table->index(['kitItemId', 'productId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitoption');
    }
};
