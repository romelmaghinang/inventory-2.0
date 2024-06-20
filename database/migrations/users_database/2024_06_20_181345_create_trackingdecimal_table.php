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
        Schema::create('trackingdecimal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('info')->nullable();
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
        Schema::dropIfExists('trackingdecimal');
    }
};
