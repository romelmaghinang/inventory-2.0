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
        Schema::create('vendorparts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultFlag');
            $table->decimal('lastCost', 28, 9)->nullable();
            $table->dateTime('lastDate')->nullable();
            $table->integer('leadTime')->nullable();
            $table->integer('partId')->unique('u_partid');
            $table->decimal('qtyMax', 28, 9)->nullable();
            $table->decimal('qtyMin', 28, 9)->nullable();
            $table->integer('uomId')->nullable()->unique('u_uomid');
            $table->integer('userId')->nullable();
            $table->integer('vendorId')->nullable()->unique('u_vendorid');
            $table->string('vendorPartNumber', 70)->nullable();

            $table->index(['userId', 'vendorId', 'uomId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendorparts');
    }
};
