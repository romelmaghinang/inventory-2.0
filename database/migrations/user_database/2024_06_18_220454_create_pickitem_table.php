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
        Schema::create('pickitem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('destTagId')->nullable();
            $table->integer('orderId');
            $table->integer('orderTypeId');
            $table->integer('partId');
            $table->integer('pickId');
            $table->integer('poItemId');
            $table->decimal('qty', 28, 9)->nullable();
            $table->integer('shipId')->nullable();
            $table->integer('slotNum')->nullable();
            $table->integer('soItemId')->nullable();
            $table->integer('srcLocationId')->nullable();
            $table->bigInteger('srcTagId')->nullable();
            $table->integer('statusId')->nullable();
            $table->bigInteger('tagId')->nullable();
            $table->integer('typeId');
            $table->integer('uomId');
            $table->integer('woItemId')->nullable();
            $table->integer('xoItemId')->nullable();

            $table->index(['partId', 'soItemId', 'statusId', 'orderTypeId', 'typeId', 'poItemId', 'pickId', 'xoItemId', 'woItemId', 'uomId', 'orderId', 'shipId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickitem');
    }
};
