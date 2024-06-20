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
        Schema::create('paymentterms', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag');
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultTerm');
            $table->double('discount')->nullable();
            $table->integer('discountDays')->nullable();
            $table->string('name', 30)->unique('u_name');
            $table->integer('netDays')->nullable();
            $table->integer('nextMonth')->nullable();
            $table->boolean('readOnly');
            $table->integer('typeId')->index('performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentterms');
    }
};
