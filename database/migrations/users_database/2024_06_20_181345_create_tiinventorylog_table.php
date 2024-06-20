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
        Schema::create('tiinventorylog', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('info', 41)->nullable();
            $table->dateTime('infoDate')->nullable();
            $table->double('infoDouble')->nullable();
            $table->integer('infoInteger')->nullable();
            $table->bigInteger('inventoryLogId');
            $table->integer('partTrackingId');
            $table->decimal('qty', 28, 9);

            $table->index(['inventoryLogId', 'partTrackingId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiinventorylog');
    }
};
