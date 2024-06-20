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
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->string('code', 10)->unique('u_code');
            $table->boolean('defaultRecord');
            $table->string('description', 256);
            $table->boolean('integral');
            $table->string('name', 30)->unique('u_name');
            $table->boolean('readOnly');
            $table->integer('uomType')->index('performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uom');
    }
};
