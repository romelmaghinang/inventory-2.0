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
        Schema::create('tablereference', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('className', 100);
            $table->integer('tableId')->index('performance');
            $table->string('tableRefName', 30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tablereference');
    }
};
