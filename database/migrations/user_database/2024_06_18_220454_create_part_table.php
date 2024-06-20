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
        Schema::create('part', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('abcCode', 1)->nullable();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag');
            $table->integer('adjustmentAccountId')->nullable();
            $table->string('alertNote', 90)->nullable();
            $table->boolean('alwaysManufacture');
            $table->integer('cogsAccountId')->nullable();
            $table->boolean('configurable');
            $table->boolean('controlledFlag');
            $table->decimal('cycleCountTol', 28, 9)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultBomId')->nullable();
            $table->integer('defaultProductId')->nullable();
            $table->string('description', 252)->nullable();
            $table->longText('details')->nullable();
            $table->decimal('height', 28, 9)->nullable();
            $table->integer('inventoryAccountId')->nullable();
            $table->string('lastChangedUser', 15)->nullable();
            $table->integer('leadTime')->nullable();
            $table->integer('leadTimeToFulfill')->nullable();
            $table->decimal('len', 28, 9)->nullable();
            $table->string('num', 70)->unique('u_num');
            $table->integer('partClassId')->nullable();
            $table->boolean('pickInUomOfPart');
            $table->decimal('receivingTol', 28, 9)->nullable();
            $table->string('revision', 15)->nullable();
            $table->integer('scrapAccountId')->nullable();
            $table->boolean('serializedFlag')->nullable();
            $table->integer('sizeUomId')->nullable();
            $table->decimal('stdCost', 28, 9)->nullable();
            $table->integer('taxId')->nullable();
            $table->boolean('trackingFlag');
            $table->integer('typeId');
            $table->integer('uomId');
            $table->string('upc', 31);
            $table->string('url', 256)->nullable();
            $table->integer('varianceAccountId')->nullable();
            $table->decimal('weight', 28, 9)->nullable();
            $table->integer('weightUomId')->nullable();
            $table->decimal('width', 28, 9)->nullable();

            $table->index(['adjustmentAccountId', 'weightUomId', 'scrapAccountId', 'typeId', 'inventoryAccountId', 'defaultProductId', 'sizeUomId', 'cogsAccountId', 'defaultBomId', 'uomId', 'taxId', 'varianceAccountId', 'description', 'num', 'upc'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part');
    }
};
