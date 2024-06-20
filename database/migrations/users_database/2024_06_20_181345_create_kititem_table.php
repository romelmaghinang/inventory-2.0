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
        Schema::create('kititem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->decimal('defaultQty', 28, 9);
            $table->string('description', 256)->nullable();
            $table->integer('discountId')->nullable();
            $table->integer('kitItemTypeId');
            $table->integer('kitProductId');
            $table->integer('kitTypeId');
            $table->decimal('maxQty', 28, 9);
            $table->decimal('minQty', 28, 9);
            $table->longText('note')->nullable();
            $table->integer('productId')->nullable();
            $table->decimal('qtyPriceAdjustment', 28, 9)->nullable();
            $table->integer('soItemTypeId')->nullable();
            $table->integer('sortOrder')->nullable();
            $table->integer('taxRateId')->nullable();
            $table->integer('uomId')->nullable();

            $table->index(['taxRateId', 'productId', 'discountId', 'kitTypeId', 'kitProductId', 'soItemTypeId', 'kitItemTypeId', 'uomId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kititem');
    }
};
