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
        Schema::create('wo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('calCategoryId')->nullable();
            $table->decimal('cost', 28, 9)->nullable();
            $table->integer('customerId')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateFinished')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateScheduled')->nullable();
            $table->dateTime('dateScheduledToStart')->nullable();
            $table->dateTime('dateStarteddatetime')->nullable();
            $table->integer('locationGroupId')->nullable();
            $table->integer('locationId')->nullable();
            $table->integer('moItemId')->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 30)->nullable()->unique('u_num');
            $table->integer('priorityId');
            $table->integer('qbClassId')->nullable();
            $table->integer('qtyOrdered')->nullable();
            $table->integer('qtyScrapped')->nullable();
            $table->integer('qtyTarget')->nullable();
            $table->integer('statusId');
            $table->integer('typeId')->nullable();
            $table->integer('userId');

            $table->index(['calCategoryId', 'moItemId', 'priorityId', 'userId', 'customerId', 'locationId', 'statusId', 'qbClassId', 'locationGroupId', 'typeId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo');
    }
};
