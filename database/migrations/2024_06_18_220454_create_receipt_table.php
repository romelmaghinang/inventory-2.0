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
        Schema::create('receipt', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('locationGroupId');
            $table->integer('orderTypeId');
            $table->integer('poId')->nullable();
            $table->integer('soId')->nullable();
            $table->integer('statusId'); 
            $table->integer('typeId');
            $table->integer('userId');
            $table->integer('xoId')->nullable();

            $table->index(['xoId', 'locationGroupId', 'typeId', 'orderTypeId', 'soId', 'statusId', 'userId', 'poId'], 'performance');

            $table->foreign('statusId')->references('id')->on('receiptstatus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt');
    }
};
