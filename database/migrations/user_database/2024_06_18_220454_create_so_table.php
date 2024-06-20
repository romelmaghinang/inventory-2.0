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
        Schema::create('so', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('billToAddress', 90)->nullable();
            $table->string('billToCity', 30)->nullable();
            $table->integer('billToCountryId');
            $table->string('billToName', 41)->nullable();
            $table->integer('billToStateId');
            $table->string('billToZip', 10)->nullable();
            $table->integer('carrierId')->nullable();
            $table->integer('carrierServiceId')->nullable();
            $table->decimal('cost', 28, 9)->nullable();
            $table->integer('currencyId')->nullable();
            $table->double('currencyRate')->nullable();
            $table->string('customerContact', 30)->nullable();
            $table->integer('customerId')->nullable();
            $table->string('customerPO', 25)->nullable();
            $table->dateTime('dateCompleted')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateExpired')->nullable();
            $table->dateTime('dateFirstShip')->nullable();
            $table->dateTime('dateIssued')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateRevision')->nullable();
            $table->string('email', 256)->nullable();
            $table->decimal('estimatedTax', 28, 9)->nullable();
            $table->integer('fobPointId')->nullable();
            $table->integer('locationGroupId')->nullable();
            $table->decimal('mcTotalTax', 28, 9)->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable()->unique('u_num');
            $table->integer('paymentTermsId')->nullable();
            $table->string('phone', 256)->nullable();
            $table->integer('priorityId')->nullable();
            $table->integer('qbClassId')->nullable();
            $table->integer('registerId')->nullable();
            $table->boolean('residentialFlag');
            $table->integer('revisionNum')->nullable();
            $table->string('salesman', 30)->nullable();
            $table->integer('salesmanId');
            $table->string('salesmanInitials', 5)->nullable();
            $table->integer('shipTermsId')->nullable();
            $table->string('shipToAddress', 90)->nullable();
            $table->string('shipToCity', 30)->nullable();
            $table->integer('shipToCountryId')->nullable();
            $table->string('shipToName', 41)->nullable();
            $table->integer('shipToStateId')->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->integer('statusId');
            $table->double('taxRate')->nullable();
            $table->integer('taxRateId')->nullable();
            $table->string('taxRateName', 31)->nullable();
            $table->boolean('toBeEmailedy');
            $table->boolean('toBePrintedy');
            $table->boolean('totalIncludesTaxy');
            $table->decimal('totalTax', 28, 9)->nullable();
            $table->decimal('subTotal', 28, 9)->nullable();
            $table->decimal('totalPrice', 28, 9)->nullable();
            $table->integer('typeId');
            $table->string('url', 256)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('vendorPO', 25)->nullable();

            $table->index(['typeId', 'qbClassId', 'statusId', 'customerId', 'taxRateId', 'paymentTermsId', 'fobPointId', 'salesmanId', 'carrierId', 'carrierServiceId', 'currencyId', 'shipTermsId', 'locationGroupId', 'priorityId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so');
    }
};
