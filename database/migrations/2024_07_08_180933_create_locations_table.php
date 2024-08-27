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
            $table->boolean('activeFlag')->default(false);
            $table->boolean('countedAsAvailable');
            $table->datetime('dateLastModified')->nullable();
            $table->integer('defaultCustomerId')->nullable();
            $table->boolean('defaultFlag')->default(true);
            $table->integer('defaultVendorId')->nullable();
            $table->string('description', 252)->nullable();
            $table->string('name', 30)->unique();
            $table->boolean('pickable');
            $table->boolean('receivable');
            $table->integer('sortOrder')->nullable();

            $table->foreignId('locationGroupId')->constrained('locationgroup');
            $table->foreignId('typeId')->constrained('locationtype');
            
            $table->index(['typeId', 'locationGroupId', 'defaultVendorId', 'defaultCustomerId', 'name'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
