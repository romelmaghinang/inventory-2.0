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
        Schema::create('taxrate', function (Blueprint $table) {
            $table->id();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag')->default(true);
            $table->string('code', 5)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultFlag')->default(true);
            $table->string('description', 255)->nullable();
            $table->string('name', 31)->nullable()->unique('u_name');
            $table->integer('orderTypeId')->nullable();
            $table->double('rate')->nullable();
            $table->integer('taxAccountId')->nullable();
            $table->string('typeCode', 25)->nullable();
            $table->bigInteger('typeId')->nullable();
            $table->dateTime('unitCost')->nullable();
            $table->integer('vendorId')->nullable();
            $table->index(['orderTypeId', 'taxAccountId', 'typeId', 'vendorId'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxrate');
    }
};
