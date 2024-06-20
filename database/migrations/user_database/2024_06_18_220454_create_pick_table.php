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
            $table->integer('id', true);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateScheduled')->nullable();
            $table->dateTime('dateStarted')->nullable();
            $table->integer('locationGroupId');
            $table->string('num', 30)->unique('u_num');
            $table->integer('priority');
            $table->integer('statusId');
            $table->integer('typeId');
            $table->integer('userId');
            $table->dateTime('dateFinished')->nullable();

            $table->index(['locationGroupId', 'priority', 'userId', 'statusId', 'typeId', 'dateFinished', 'dateScheduled', 'dateStarted'], 'performance');
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
