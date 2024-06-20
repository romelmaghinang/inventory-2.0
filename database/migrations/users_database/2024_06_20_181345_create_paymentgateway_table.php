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
        Schema::create('paymentgateway', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('accountType')->nullable();
            $table->string('javaClass');
            $table->string('login');
            $table->string('name')->unique('u_name');
            $table->string('other')->nullable();
            $table->string('secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentgateway');
    }
};
