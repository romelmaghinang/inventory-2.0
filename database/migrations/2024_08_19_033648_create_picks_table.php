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
            $table->timestamp('dateCreated')->useCurrent();
            $table->dateTime('dateFinished')->nullable();
            $table->timestamp('dateLastModified')->useCurrent();
            $table->dateTime('dateScheduled')->nullable();
            $table->dateTime('dateStarted')->nullable();
            $table->string('num', 35)->nullable();
            $table->unsignedBigInteger('userId')->nullable();

            $table->unsignedBigInteger('locationGroupId');
            $table->foreignId('statusId')->default(10)->constrained('pickstatus');
            $table->foreignId('typeId')->default(10)->constrained('picktype');
            $table->foreignId('priority')->default(30)->constrained('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picks');
    }
};
