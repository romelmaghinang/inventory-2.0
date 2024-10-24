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
            $table->integer('id', true);
            $table->string('accountingHash', 41)->nullable();
            $table->string('accountingId', 41)->nullable();
            $table->boolean('activeFlag');
            $table->string('alertNote', 90)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultSoItemType');
            $table->string('description', 252)->nullable();
            $table->longText('details')->nullable();
            $table->integer('displayTypeId');
            $table->decimal('heigh', 28, 9)->nullable();
            $table->integer('incomeAccountId');
            $table->boolean('kitFlag');
            $table->boolean('kitGroupedFlag');
            $table->decimal('len', 28, 9)->nullable();
            $table->string('num', 70)->nullable()->unique('u_num');
            $table->integer('partId')->nullable();
            $table->decimal('price', 28, 9)->nullable();
            $table->integer('qbClassId')->nullable();
            $table->boolean('sellableInOtherUoms');
            $table->boolean('showSoComboFlag');
            $table->integer('sizeUomId')->nullable();
            $table->string('sku', 41)->nullable();
            $table->integer('taxId')->nullable();
            $table->boolean('taxableFlag');
            $table->integer('uomId');
            $table->string('upc', 41)->nullable();
            $table->string('url', 256)->nullable();
            $table->boolean('usePriceFlag');
            $table->decimal('weight', 28, 9)->nullable();
            $table->integer('weightUomId')->nullable();
            $table->decimal('width', 28, 9)->nullable();

            $table->index(['weightUomId', 'uomId', 'qbClassId', 'partId', 'incomeAccountId', 'displayTypeId', 'defaultSoItemType', 'taxId', 'sizeUomId', 'description', 'num', 'showSoComboFlag', 'sku', 'upc'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
