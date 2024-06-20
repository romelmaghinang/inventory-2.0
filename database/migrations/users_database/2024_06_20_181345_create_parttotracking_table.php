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
        Schema::create('parttotracking', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nextValue', 41)->nullable();
            $table->integer('partId')->unique('u_partid');
            $table->integer('partTrackingId')->index('performance');
            $table->boolean('primaryFlag')->nullable();

            $table->unique(['partTrackingId'], 'u_parttrackingid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parttotracking');
    }
};
