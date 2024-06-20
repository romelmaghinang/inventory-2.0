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
        Schema::create('trackingdate', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('info')->nullable();
            $table->integer('partTrackingId');
            $table->bigInteger('tagId');

            $table->index(['partTrackingId', 'tagId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackingdate');
    }
};
