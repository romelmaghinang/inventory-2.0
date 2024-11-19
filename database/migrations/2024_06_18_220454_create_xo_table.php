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
        Schema::create('xo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('carrierId')->nullable();
            $table->dateTime('dateCompleted')->nullable();
            $table->dateTime('dateConfirmed')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateFirstShip')->nullable();
            $table->dateTime('dateIssued')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateScheduled')->nullable();
            $table->string('fromAddress', 90)->nullable();
            $table->string('fromAttn', 90)->nullable();
            $table->string('fromCity', 30)->nullable();
            $table->integer('fromCountryId')->nullable();
            $table->integer('fromLGId');
            $table->string('fromName', 41)->nullable();
            $table->integer('fromStateId')->nullable();
            $table->string('fromZip', 10)->nullable();
            $table->bigInteger('mainLocationTagId');
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable()->unique('u_num');
            $table->boolean('ownerIsFrom')->nullable();
            $table->integer('revisionNum')->nullable();
            $table->string('shipToAddress', 90)->nullable();
            $table->string('shipToAttn', 30)->nullable();
            $table->string('shipToCity', 30)->nullable();
            $table->integer('shipToCountryId')->nullable();
            $table->integer('shipToLGId');
            $table->string('shipToName', 41)->nullable();
            $table->integer('shipToStateId')->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->integer('statusId');
            $table->integer('typeId');
            $table->integer('userId');

            $table->index(['fromLGId', 'typeId', 'statusId', 'carrierId', 'userId', 'mainLocationTagId', 'shipToLGId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xo');
    }
};
