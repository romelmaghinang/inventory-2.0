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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('accountingHash', 41)->nullable();
            $table->string('accountingId', 41)->nullable();
            $table->boolean('activeFlag')->default(0);
            $table->string('alertNote', 90)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultSoItemType');
            $table->string('description', 252)->nullable();
            $table->longText('details');
            $table->integer('displayTypeId')->nullable();
            $table->decimal('height', 28, 9)->nullable();
            $table->integer('incomeAccountId')->nullable();
            $table->boolean('kitFlag')->default(0);
            $table->boolean('kitGroupedFlag')->default(0);
            $table->decimal('length', 28, 9)->nullable();
            $table->string('num', 70)->nullable();
            $table->integer('partId')->nullable();
            $table->decimal('price', 28, 9)->nullable();
            $table->integer('qbClassId')->nullable();
            $table->boolean('sellableInOtherUoms')->default(0);
            $table->boolean('showSoComboFlag')->default(0);
            $table->integer('sizeUomId')->nullable();
            $table->string('sku', 41)->nullable();
            $table->integer('taxId')->nullable();
            $table->boolean('taxableFlag')->default(0);
            $table->integer('uomId');
            $table->string('upc', 41)->nullable();
            $table->string('url', 256)->nullable();
            $table->boolean('usePriceFlag')->default(0);
            $table->decimal('weight', 28, 9)->nullable();
            $table->integer('weightUomId')->nullable();
            $table->decimal('width', 28, 9)->nullable();
            $table->string('cf')->nullable();
            $table->unique('num');
            $table->index([
                'weightUomId', 'uomId', 'qbClassId', 'partId', 'incomeAccountId', 'displayTypeId', 'defaultSoItemType', 'taxId', 'sizeUomId', 
                'description', 'num', 'showSoComboFlag', 'sku', 'upc'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
