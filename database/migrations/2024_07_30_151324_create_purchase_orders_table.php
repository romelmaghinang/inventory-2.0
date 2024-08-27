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
            $table->id();
            $table->string('buyer', 30)->nullable();
            $table->unsignedInteger('buyerId');
            $table->unsignedInteger('carrierId');
            $table->unsignedInteger('currencyId')->nullable();
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
            $table->string('email', 255)->nullable();
            $table->unsignedInteger('fobPointId')->nullable();
            $table->unsignedInteger('locationGroupId')->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable()->unique();
            $table->unsignedInteger('paymentTermsId')->nullable();
            $table->string('phone', 256)->nullable();
            $table->unsignedInteger('qbClassId')->nullable();
            $table->string('remitAddress', 90)->nullable();
            $table->string('remitCity', 30)->nullable();
            $table->unsignedInteger('remitCountryId')->nullable();
            $table->unsignedInteger('remitStateId')->nullable();
            $table->string('remitToName', 41)->nullable();
            $table->string('remitZip', 10)->nullable();
            $table->unsignedInteger('revisionNum')->nullable();
            $table->unsignedInteger('shipTermsId');
            $table->string('shipToAddress', 90)->nullable();
            $table->unsignedInteger('shipToCity')->nullable();
            $table->unsignedInteger('shipToCountryId')->nullable();
            $table->string('shipToName', 41)->nullable();
            $table->unsignedInteger('shipToStateId')->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->unsignedInteger('statusId')->nullable();
            $table->unsignedInteger('taxRateId');
            $table->string('taxRateName', 31)->nullable();
            $table->boolean('totalIncludesTax')->nullable();
            $table->decimal('totalTax', 28, 9)->nullable();
            $table->unsignedInteger('typeId')->nullable();
            $table->string('url', 256)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('vendorContact', 30)->nullable();
            $table->unsignedInteger('vendorId');
            $table->string('vendorSO', 25)->nullable();

            $table->unique('num', 'u_num');
            $table->index([
                'carrierId',
                'fobPointId',
                'paymentTermsId',
                'buyerId',
                'taxRateId',
                'qbClassId',
                'locationGroupId',
                'vendorId',
                'typeId',
                'statusId',
                'currencyId',
                'shipTermsId'
            ], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
