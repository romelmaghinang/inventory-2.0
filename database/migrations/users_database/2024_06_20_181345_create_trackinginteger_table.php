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
        Schema::create('trackinginteger', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('info');
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
        Schema::dropIfExists('trackinginteger');
    }
};
