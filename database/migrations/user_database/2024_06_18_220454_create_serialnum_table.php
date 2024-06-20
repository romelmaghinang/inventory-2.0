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
        Schema::create('serialnum', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('partTrackingId');
            $table->bigInteger('serialId');
            $table->string('serialNum', 41)->nullable();

            $table->index(['serialId', 'partTrackingId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serialnum');
    }
};
