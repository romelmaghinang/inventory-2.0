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
            $table->id();
            $table->decimal('adjustAmount', 28, 9)->nullable();
            $table->decimal('adjustPercentage', 28, 9)->nullable();
            $table->string('customerPartNum', 70)->nullable();
            $table->datetime('dateLastFulfillment')->nullable();
            $table->datetime('dateLastModified')->nullable();
            $table->datetime('dateScheduledFulfillment')->nullable();
            $table->string('description', 256)->nullable();
            $table->integer('exchangeSOLineItem')->nullable();
            $table->integer('itemAdjustId')->nullable();
            $table->decimal('markupCost', 28, 9)->nullable();
            $table->decimal('mcTotalPrice', 28, 9)->nullable();
            $table->longText('note');
            $table->string('productNum', 70)->nullable();
            $table->decimal('qtyFulfilled', 28, 9)->nullable();
            $table->decimal('qtyOrdered', 28, 9)->nullable();
            $table->decimal('qtyPicked', 28, 9)->nullable();
            $table->decimal('qtyToFulfill', 28, 9)->nullable();
            $table->string('revLevel', 15)->nullable();
            $table->boolean('showItemFlag')->default(true);
            $table->integer('soLineItem')->nullable();
            $table->float('taxRate', 8, 8)->nullable();
            $table->boolean('taxableFlag')->nullable();
            $table->decimal('totalCost', 28, 9)->nullable();
            $table->decimal('totalPrice', 28, 9)->nullable();
            $table->decimal('unitPrice', 28, 9)->nullable();
            $table->string('customFieldItem')->nullable();

            $table->foreignId('soId')->constrained('so');
            $table->foreignId('qbClassId')->nullable()->constrained('qbclass');
            $table->foreignId('productId')->nullable()->constrained('product');
            $table->foreignId('statusId')->constrained('sostatus');
            $table->foreignId('uomId')->nullable()->constrained('uom');
            $table->foreignId('typeId')->constrained('soitemtype');
            $table->foreignId('taxId')->nullable()->constrained('taxrate');

        });
    }

    /**
     * Reverse the migrations.
     * 
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
