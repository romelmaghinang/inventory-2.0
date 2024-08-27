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
            $table->id();
            $table->decimal('qty', 28, 9)->nullable();
            $table->integer('slotNum')->nullable();
            $table->integer('srcLocationId')->nullable();
            $table->bigInteger('srcTagId')->nullable();
            $table->bigInteger('tagId')->nullable();

            $table->unsignedBigInteger('destTagId');
            $table->unsignedBigInteger('orderId');
            $table->unsignedBigInteger('shipId')->nullable();

            $table->foreignId('orderTypeId')->constrained('ordertype');
            $table->foreignId('partId')->constrained('part');
            $table->foreignId('pickId')->constrained('pick');
            $table->foreignId('poItemId')->nullable()->constrained('poitem');
            $table->foreignId('soItemId')->nullable()->constrained('soitem');
            $table->foreignId('statusId')->nullable()->constrained('pickitemstatus');
            $table->foreignId('typeId')->constrained('pickitemtype');
            $table->foreignId('uomId')->constrained('uom');
            $table->foreignId('woItemId')->nullable()->constrained('woitem');
            $table->foreignId('xoItemId')->nullable()->constrained('xoitem');

            $table->index([
                'partId',
                'soItemId',
                'statusId',
                'orderTypeId',
                'typeId',
                'poItemId',
                'pickId',
                'xoItemId',
                'woItemId',
                'uomId',
                'orderId',
                'shipId'
            ], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick_items');
    }
};
