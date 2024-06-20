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
        Schema::create('carrierservice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->integer('carrierId')->index('performance');
            $table->string('code')->unique('u_code');
            $table->string('name')->unique('u_name');
            $table->boolean('readOnly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrierservice');
    }
};
