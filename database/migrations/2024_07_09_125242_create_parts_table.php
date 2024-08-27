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
            $table->id();
            $table->string('abcCode', 1)->nullable();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag');
            $table->unsignedInteger('adjustmentAccountId')->nullable();
            $table->string('alertNote', 256)->nullable();
            $table->boolean('alwaysManufacture')->default(true);
            $table->unsignedInteger('cogsAccountId')->nullable();
            $table->boolean('configurable')->default(true);
            $table->decimal('consumptionRate', 28, 9)->default(false);
            $table->boolean('controlledFlag')->default(true);
            $table->decimal('cycleCountTol', 28, 9)->nullable();
            $table->dateTime('dateCreated', 6)->nullable();
            $table->dateTime('dateLastModified', 6)->nullable();
            $table->unsignedInteger('defaultBomId')->nullable();
            $table->unsignedInteger('defaultOutsourcedReturnItemId')->nullable();
            $table->unsignedInteger('defaultPoItemTypeId')->nullable();
            $table->unsignedInteger('defaultProductId')->nullable();
            $table->string('description', 252)->nullable();
            $table->longText('details')->nullable();
            $table->decimal('height', 28, 9)->nullable();
            $table->unsignedInteger('inventoryAccountId')->nullable();
            $table->string('lastChangedUser', 100)->nullable();
            $table->integer('leadTime')->nullable();
            $table->decimal('length', 28, 9)->nullable();
            $table->string('num', 70)->unique();
            $table->unsignedInteger('partClassId')->nullable();
            $table->boolean('pickInUomOfPart')->default(true);
            $table->decimal('receivingTol', 28, 9)->nullable();
            $table->string('revision', 15)->nullable();
            $table->unsignedInteger('scrapAccountId')->nullable();
            $table->boolean('serializedFlag')->default(true);
            $table->unsignedInteger('sizeUomId')->nullable();
            $table->decimal('stdCost', 28, 9)->nullable();
            $table->boolean('trackingFlag')->default(true);
            $table->string('upc', 31)->nullable();
            $table->string('url', 256)->nullable();
            $table->unsignedInteger('varianceAccountId')->nullable();
            $table->decimal('weight', 28, 9)->nullable();
            $table->unsignedInteger('weightUomId')->nullable();
            $table->decimal('width', 28, 9)->nullable();
            $table->json('customFields')->nullable();
            $table->index('num', 'partNumIdx');
            $table->index('description', 'partDescriptionIdx');
            $table->index('upc', 'partUPCIdx');

            $table->foreignId('uomId')->constrained('uom');
            $table->foreignId('typeId')->constrained('parttype');
            $table->foreignId('taxId')->nullable()->constrained('taxrate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
