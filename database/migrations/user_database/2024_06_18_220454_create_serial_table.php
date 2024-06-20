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
        Schema::create('serial', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('committedFlag');
            $table->bigInteger('tagId')->unique('u_tagid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serial');
    }
};
