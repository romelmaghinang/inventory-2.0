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
        Schema::create('soitem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('adjustAmount', 28, 9)->nullable();
            $table->decimal('adjustPercentage', 28, 9)->nullable();
            $table->string('customerPartNum', 70)->nullable();
            $table->dateTime('dateLastFulfillment')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateScheduledFulfillment')->nullable();
            $table->string('description', 256)->nullable();
            $table->integer('exchangeSOLineItem')->nullable();
            $table->integer('itemAdjustId')->nullable();
            $table->decimal('markupCost', 28, 9)->nullable();
            $table->decimal('mcTotalPrice', 28, 9)->nullable();
            $table->longText('note')->nullable();
            $table->integer('productId')->nullable();
            $table->string('productNum', 70)->nullable();
            $table->integer('qbClassId')->nullable();
            $table->decimal('qtyFulfilled', 28, 9)->nullable();
            $table->decimal('qtyOrdered', 28, 9)->nullable();
            $table->decimal('qtyPicked', 28, 9)->nullable();
            $table->decimal('qtyToFulfill', 28, 9)->nullable();
            $table->string('revLevel', 15)->nullable();
            $table->boolean('showItemFlag');
            $table->integer('soId');
            $table->integer('soLineItem');
            $table->integer('statusId');
            $table->integer('taxId')->nullable();
            $table->double('taxRate')->nullable();
            $table->boolean('taxableFlag');
            $table->decimal('totalCost', 28, 9)->nullable();
            $table->decimal('totalPrice', 28, 9)->nullable();
            $table->integer('typeId');
            $table->decimal('unitPrice', 28, 9)->nullable();
            $table->integer('uomId')->nullable();

            $table->index(['taxId', 'qbClassId', 'productId', 'soId', 'itemAdjustId', 'statusId', 'uomId', 'typeId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soitem');
    }
};
