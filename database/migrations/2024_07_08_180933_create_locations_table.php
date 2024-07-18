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
            $table->id();
            $table->boolean('activeFlag')->notnull();
            $table->boolean('countedAsAvailable')->notnull();
            $table->datetime('dateLastModified')->nullable();
            $table->integer('defaultCustomerId')->nullable();
            $table->boolean('defaultFlag')->notnull();
            $table->integer('defaultVendorId')->nullable();
            $table->string('description', 252)->nullable();
            $table->integer('locationGroupId')->notnull();
            $table->string('name', 30)->notnull()->unique();
            $table->boolean('pickable')->notnull();
            $table->boolean('receivable')->notnull();
            $table->integer('sortOrder')->nullable();
            $table->integer('typeId')->notnull();
            $table->index(['typeId', 'locationGroupId', 'defaultVendorId', 'defaultCustomerId', 'name'], 'Performance');
            $table->timestamps();
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
