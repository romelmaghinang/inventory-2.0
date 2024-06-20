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
        Schema::create('cartontype', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('defaultFlag');
            $table->string('description', 252)->nullable();
            $table->decimal('height', 28, 9)->nullable();
            $table->decimal('len', 28, 9)->nullable();
            $table->string('name', 70)->unique('u_name');
            $table->string('sizeUOM', 30)->nullable();
            $table->decimal('width', 28, 9)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartontype');
    }
};
