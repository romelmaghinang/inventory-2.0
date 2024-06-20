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
        Schema::create('location', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->boolean('countedAsAvailable');
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultCustomerId')->nullable();
            $table->boolean('defaultFlag');
            $table->integer('defaultVendorId')->nullable();
            $table->string('description', 252)->nullable();
            $table->integer('locationGroupId')->unique('u_locationgroupid');
            $table->string('name', 30)->unique('u_name');
            $table->integer('parentId')->nullable();
            $table->boolean('pickable');
            $table->boolean('receivable');
            $table->integer('sortOrder')->nullable();
            $table->integer('typeId');

            $table->index(['typeId', 'locationGroupId', 'defaultVendorId', 'defaultCustomerId', 'name'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location');
    }
};
