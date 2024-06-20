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
            $table->integer('id')->primary();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag');
            $table->string('code', 5)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultFlag');
            $table->string('description')->nullable();
            $table->string('name', 31)->nullable()->unique('u_name');
            $table->integer('orderTypeId')->nullable();
            $table->double('rate')->nullable();
            $table->integer('taxAccountId')->nullable();
            $table->string('typeCode', 25)->nullable();
            $table->integer('typeId')->nullable();
            $table->dateTime('unitCost')->nullable();
            $table->integer('vendorId')->nullable();

            $table->index(['orderTypeId', 'taxAccountId', 'typeId', 'vendorId'], 'performance');
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
