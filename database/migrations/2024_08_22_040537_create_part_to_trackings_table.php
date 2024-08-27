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
            $table->id();
            $table->string('nextValue', 41)->nullable();
            $table->boolean('primaryFlag')->nullable();

            $table->foreignId('partTrackingId')->constrained('parttracking');
            $table->foreignId('partId')->constrained('part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_to_trackings');
    }
};
