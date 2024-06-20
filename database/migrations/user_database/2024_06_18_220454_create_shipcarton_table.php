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
        Schema::create('shipcarton', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('additionalHandling');
            $table->integer('carrierId');
            $table->integer('cartonNum');
            $table->integer('cartonTypeId')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->integer('deliveryConfirmationId');
            $table->decimal('freightAmount', 28, 9)->nullable();
            $table->decimal('freightWeight', 28, 9)->nullable();
            $table->decimal('height', 28, 9)->nullable();
            $table->decimal('insuredValue', 28, 9)->nullable();
            $table->decimal('len', 28, 9)->nullable();
            $table->integer('orderId')->nullable();
            $table->integer('orderTypeId');
            $table->integer('packageTypeId');
            $table->integer('shipId');
            $table->boolean('shipperRelease');
            $table->string('sizeUOM', 35)->nullable();
            $table->string('sscc', 35)->nullable();
            $table->string('trackingNum')->nullable();
            $table->string('weightUOM', 32)->nullable();
            $table->decimal('width', 28, 9)->nullable();

            $table->index(['carrierId', 'deliveryConfirmationId', 'shipId', 'packageTypeId', 'orderTypeId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipcarton');
    }
};
