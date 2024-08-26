<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accountId')->nullable();
            $table->string('name', 41);
            $table->string('city', 30)->nullable();
            $table->boolean('defaultFlag')->default(true);
            $table->string('addressName', 90)->nullable()->unique();
            $table->integer('pipelineContactNum')->nullable();
            $table->string('address', 90);
            $table->string('zip', 10)->nullable();

            $table->foreignId('countryId')->nullable()->constrained('country');
            $table->foreignId('typeId')->nullable()->constrained('addresstype');
            $table->foreignId('stateId')->nullable()->constrained('state');
            $table->foreignId('locationGroupId')->nullable()->constrained('locationgroup');

            $table->index(['accountId', 'locationGroupId', 'stateId', 'typeID', 'countryId'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
