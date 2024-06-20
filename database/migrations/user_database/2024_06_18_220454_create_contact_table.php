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
        Schema::create('contact', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accountId');
            $table->integer('addressId')->nullable();
            $table->string('datus', 64)->nullable();
            $table->boolean('defaultFlag')->nullable();
            $table->string('contactName', 30)->nullable();
            $table->integer('typeId');

            $table->index(['accountId', 'typeId', 'addressId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
