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
        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastCycleCount')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('num')->nullable()->unique();
            $table->decimal('qty', 28, 9)->nullable();
            $table->decimal('qtyCommitted', 28, 9)->default(0.0000);
            $table->boolean('serializedFlag');
            $table->string('trackingEncoding', 30)->nullable();
            $table->boolean('usedFlag');
            
            $table->unsignedInteger('woItemId')->nullable();
            $table->unsignedInteger('partId')->nullable();
            $table->unsignedInteger('typeId');
            $table->unsignedInteger('locationId');

            $table->index(['locationId', 'woItemId', 'partId', 'typeId', 'dateLastCycleCount', 'num'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
