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
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accountId')->unique('u_accountid');
            $table->string('name', 41);
            $table->string('city', 30)->nullable();
            $table->integer('countryId')->nullable();
            $table->boolean('defaultFlag');
            $table->integer('locationGroupId')->nullable();
            $table->string('addressName', 90)->nullable()->unique('u_addressname');
            $table->integer('pipelineContactNum')->nullable();
            $table->integer('stateId')->nullable();
            $table->string('address', 90);
            $table->integer('typeID')->nullable();
            $table->string('zip', 10)->nullable();

            $table->index(['accountId', 'locationGroupId', 'stateId', 'typeID', 'countryId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
