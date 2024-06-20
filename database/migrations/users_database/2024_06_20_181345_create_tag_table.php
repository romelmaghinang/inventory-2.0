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
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastCycleCount')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('locationId');
            $table->bigInteger('num')->nullable()->unique('u_num');
            $table->integer('partId')->nullable();
            $table->decimal('qty', 28, 9)->nullable();
            $table->decimal('qtyCommitted', 28, 9)->nullable();
            $table->boolean('serializedFlag');
            $table->string('trackingEncoding', 30);
            $table->integer('typeId');
            $table->boolean('usedFlag');
            $table->integer('woItemId')->nullable();

            $table->index(['locationId', 'woItemId', 'partId', 'typeId', 'dateLastCycleCount', 'num'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag');
    }
};
