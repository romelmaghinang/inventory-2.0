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
        Schema::create('paymentmethod', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('active');
            $table->boolean('editable');
            $table->string('name', 30)->nullable()->unique('u_name');
            $table->integer('typeId')->index('performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentmethod');
    }
};
