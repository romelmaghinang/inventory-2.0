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
        Schema::create('trackinginfosn', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('partTrackingId');
            $table->string('serialNum', 41)->nullable();
            $table->bigInteger('trackingInfoId');

            $table->index(['partTrackingId', 'trackingInfoId', 'serialNum'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackinginfosn');
    }
};
