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
        Schema::create('shipitem', function (Blueprint $table) {
            $table->id();
            $table->timestamp('dateLastModified')->useCurrent();
            $table->integer('itemId')->nullable();
            $table->integer('orderId')->nullable();
            $table->integer('orderTypeId')->nullable();
            $table->integer('poItemId')->nullable();
            $table->decimal('qtyShipped', 28, 9)->nullable();
            $table->integer('shipCartonId');
            $table->integer('shipId');
            $table->integer('soItemId')->nullable();
            $table->bigInteger('tagId')->nullable();
            $table->decimal('totalCost', 28, 9)->nullable();
            $table->integer('uomId');
            $table->integer('xoItemId')->nullable();
            
            $table->index(['poItemId', 'uomId', 'shipId', 'xoItemId', 'soItemId', 'shipCartonId', 'orderTypeId'], 'Performance');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_items');
    }
};
