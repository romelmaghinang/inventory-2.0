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
        Schema::create('parttracking', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('abbr', 41);
            $table->boolean('activeFlag');
            $table->string('description', 256)->nullable();
            $table->string('name', 41)->unique('u_name');
            $table->integer('sortOrder');
            $table->integer('typeId')->index('performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parttracking');
    }
};
