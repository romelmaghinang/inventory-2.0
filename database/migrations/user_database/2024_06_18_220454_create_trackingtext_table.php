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
        Schema::create('trackingtext', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('info', 41)->nullable();
            $table->integer('partTrackingId')->nullable();
            $table->bigInteger('tagId');

            $table->index(['partTrackingId', 'tagId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackingtext');
    }
};
