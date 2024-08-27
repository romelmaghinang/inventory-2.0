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
        Schema::create('pick', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateFinished')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateScheduled')->nullable();
            $table->dateTime('dateStarted')->nullable();
            $table->string('num', 35)->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            
            $table->foreignId('locationGroupId')->constrained('locationgroup');
            $table->foreignId('statusId')->constrained('pickstatus');
            $table->foreignId('typeId')->constrained('picktype');
            $table->foreignId('priority')->constrained('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick');
    }
};