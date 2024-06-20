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
        Schema::create('memo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->longText('memo')->nullable();
            $table->integer('recordId');
            $table->integer('tableId');
            $table->string('userName', 15)->nullable();

            $table->index(['recordId', 'tableId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo');
    }
};
