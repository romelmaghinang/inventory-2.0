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
        Schema::create('po', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('buyer', 30)->nullable();
            $table->integer('buyerId');
            $table->integer('carrierId');
            $table->integer('currencyId')->nullable();
            $table->double('currencyRate')->nullable();
            $table->string('customerSO', 25)->nullable();
            $table->dateTime('dateCompleted')->nullable();
            $table->dateTime('dateConfirmed')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateFirstShip')->nullable();
            $table->dateTime('dateIssued')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateRevision')->nullable();
            $table->string('deliverTo', 30)->nullable();
            $table->string('email')->nullable();
            $table->integer('fobPointId')->nullable();
            $table->integer('locationGroupId')->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable()->unique('u_num');
            $table->integer('paymentTermsId')->nullable();
            $table->string('phone', 256)->nullable();
            $table->integer('qbClassId')->nullable();
            $table->string('remitAddress', 90)->nullable();
            $table->string('remitCity', 30)->nullable();
            $table->integer('remitCountryId')->nullable();
            $table->integer('remitStateId')->nullable();
            $table->string('remitToName', 41)->nullable();
            $table->string('remitZip', 10)->nullable();
            $table->integer('revisionNum')->nullable();
            $table->integer('shipTermsId');
            $table->string('shipToAddress', 90)->nullable();
            $table->integer('shipToCity')->nullable();
            $table->integer('shipToCountryId')->nullable();
            $table->string('shipToName', 41)->nullable();
            $table->integer('shipToStateId')->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->integer('statusId')->nullable();
            $table->integer('taxRateId');
            $table->string('taxRateName', 31)->nullable();
            $table->boolean('totalIncludesTax')->nullable();
            $table->decimal('totalTax', 28, 9)->nullable();
            $table->integer('typeId')->nullable();
            $table->string('url', 256)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('vendorContact', 30)->nullable();
            $table->integer('vendorId');
            $table->string('vendorSO', 25)->nullable();

            $table->index(['carrierId', 'fobPointId', 'paymentTermsId', 'buyerId', 'taxRateId', 'qbClassId', 'locationGroupId', 'vendorId', 'typeId', 'statusId', 'currencyId', 'shipTermsId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po');
    }
};
