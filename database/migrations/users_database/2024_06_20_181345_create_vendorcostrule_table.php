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
        Schema::create('vendorcostrule', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->string('description', 252)->nullable();
            $table->string('name', 70)->nullable();
            $table->integer('partId')->unique('u_partid');
            $table->decimal('qty', 28, 9)->nullable();
            $table->decimal('unitCost', 28, 9)->nullable();
            $table->integer('userId')->unique('u_userid');
            $table->integer('vendorId')->unique('u_vendorid');

            $table->index(['name', 'vendorId', 'partId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendorcostrule');
    }
};
