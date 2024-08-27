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
        Schema::create('locationgroup', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(false);
            $table->dateTime('dateLastModified')->nullable();
            $table->string('name', 30)->unique();
            $table->unsignedBigInteger('qbClassId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_groups');
    }
};
