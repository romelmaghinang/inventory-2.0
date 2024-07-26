<?php

use App\Models\AccountType;
use App\Models\State;
use App\Models\Country;
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
            $table->integer('accountId')->unsigned();
            $table->string('name', 41);
            $table->string('city', 30)->nullable();
            $table->integer('countryId')->unsigned()->nullable();
            $table->boolean('defaultFlag')->default(true);;
            $table->integer('locationGroupId')->unsigned()->nullable();
            $table->string('addressName', 90)->nullable();
            $table->integer('pipelineContactNum')->nullable();
            $table->integer('stateId')->unsigned()->nullable();
            $table->string('address', 90);
            $table->integer('typeID')->unsigned()->nullable();
            $table->string('zip', 10)->nullable();
            $table->index(['accountId', 'locationGroupId', 'stateId', 'typeID', 'countryId'], 'Performance');
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
