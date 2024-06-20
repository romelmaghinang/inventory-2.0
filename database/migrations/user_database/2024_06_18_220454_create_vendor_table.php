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
        Schema::create('vendor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accountId');
            $table->string('accountNum', 30)->nullable();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 30)->nullable();
            $table->boolean('activeFlag');
            $table->decimal('creditLimit', 28, 9)->nullable();
            $table->integer('currencyId')->nullable();
            $table->double('currencyRate')->nullable();
            $table->dateTime('dateEntered')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultCarrierId');
            $table->integer('defaultPaymentTermsId');
            $table->integer('defaultShipTermsId');
            $table->string('lastChangedUser', 30)->nullable();
            $table->integer('leadTime')->nullable();
            $table->decimal('minOrderAmount', 28, 9)->nullable();
            $table->string('name', 41)->unique('u_name');
            $table->string('note', 90)->nullable();
            $table->integer('statusId');
            $table->integer('sysUserId')->nullable();
            $table->integer('taxRateId')->nullable();
            $table->string('url', 256)->nullable();

            $table->index(['defaultCarrierId', 'statusId', 'defaultShipTermsId', 'taxRateId', 'accountId', 'defaultPaymentTermsId', 'currencyId', 'accountNum'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
