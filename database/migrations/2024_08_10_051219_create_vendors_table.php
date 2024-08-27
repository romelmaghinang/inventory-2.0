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
            $table->id();
            $table->string('accountNum', 30)->nullable();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 30)->nullable();
            $table->boolean('activeFlag');
            $table->decimal('creditLimit', 28, 9)->nullable();
            $table->double('currencyRate')->nullable();
            $table->dateTime('dateEntered')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->string('lastChangedUser', 30)->nullable();
            $table->unsignedInteger('leadTime')->nullable();
            $table->decimal('minOrderAmount', 28, 9)->nullable();
            $table->string('name', 41)->unique();
            $table->string('note', 90)->nullable();
            $table->string('url', 256)->nullable();
            $table->string('cf')->nullable();

            $table->unsignedInteger('accountId')->nullable();
            $table->foreignId('currencyId')->nullable()->constrained('currency');
            $table->foreignId('defaultCarrierId')->constrained('carrier');
            $table->foreignId('defaultPaymentTermsId')->nullable()->constrained('paymentterms');
            $table->foreignId('defaultShipTermsId')->constrained('shipterms');
            $table->foreignId('statusId')->constrained('vendorstatus');
            $table->unsignedInteger('sysUserId')->nullable();
            $table->foreignId('taxRateId')->nullable()->constrained('taxrate');

            $table->index(['defaultCarrierId', 'statusId', 'defaultShipTermsId', 'taxRateId', 'accountId', 'defaultPaymentTermsId', 'currencyId', 'accountNum'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
