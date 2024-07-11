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
            $table->id();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag')->notnull();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultTerm')->notnull();
            $table->float('discount', 8, 2)->nullable();
            $table->integer('discountDays')->nullable();
            $table->string('name', 30)->notnull()->unique('u_name');
            $table->integer('netDays')->nullable();
            $table->integer('nextMonth')->nullable();
            $table->boolean('readOnly')->notnull();
            $table->integer('typeId')->notnull();
            $table->index('typeId', 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};
