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
        Schema::create('uom', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag');
            $table->string('code', 10)->unique();
            $table->boolean('defaultRecord')->default(true);
            $table->string('description', 256);
            $table->boolean('integral')->default(true);
            $table->string('name', 30)->unique();
            $table->boolean('readOnly');
            $table->unsignedInteger('uomType');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_of_measures');
    }
};
