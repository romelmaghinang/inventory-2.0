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
        Schema::create('poitem', function (Blueprint $table) {
            $table->id();
            $table->integer('customerId')->nullable();
            $table->dateTime('dateLastFulfillment')->nullable();
            $table->dateTime('dateScheduledFulfillment')->nullable();
            $table->string('description', 256)->nullable();
            $table->longText('note')->nullable();
            $table->integer('partId')->nullable();
            $table->string('partNum', 70)->nullable();
            $table->integer('poId');
            $table->integer('poLineItem')->nullable();
            $table->integer('qbClassId')->nullable();
            $table->decimal('qtyFulfilled', 28, 9)->nullable();
            $table->decimal('qtyPicked', 28, 9)->nullable();
            $table->decimal('qtyToFulfill', 28, 9)->nullable();
            $table->boolean('repairFlag')->nullable();
            $table->string('revLevel', 15)->nullable();
            $table->integer('statusId')->nullable();
            $table->integer('taxId')->nullable();
            $table->double('taxRate')->nullable();
            $table->boolean('tbdCostFlag')->nullable();
            $table->decimal('totalCost', 28, 9)->nullable();
            $table->integer('typeId')->nullable();
            $table->decimal('unitCost', 28, 9)->nullable();
            $table->integer('uomId')->nullable();
            $table->string('vendorPartNum', 70)->nullable();

            $table->index(['customerId', 'poId', 'partId', 'taxId', 'typeId', 'statusId', 'qbClassId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poitem');
    }
};
