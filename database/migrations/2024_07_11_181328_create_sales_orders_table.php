<?php

use App\Models\AccountType;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\State;
use App\Models\Tax;
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
            $table->id();
            $table->string('billToAddress', 90)->nullable();
            $table->string('billToCity', 30)->nullable();
            $table->unsignedBigInteger('billToCountryId')->nullable();
            $table->string('billToName', 41)->nullable();
            $table->unsignedBigInteger('billToStateId')->nullable();
            $table->string('billToZip', 10)->nullable();
            $table->unsignedBigInteger('carrierId')->nullable();
            $table->unsignedBigInteger('carrierServiceId')->nullable();
            $table->decimal('cost', 28, 9)->nullable();
            $table->unsignedBigInteger('currencyId')->nullable();
            $table->double('currencyRate')->nullable();
            $table->string('customerContact', 30)->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
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
            $table->unsignedBigInteger('locationGroupId')->nullable();
            $table->decimal('mcTotalTax', 28, 9)->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable();
            $table->unsignedBigInteger('paymentTermsId')->nullable();
            $table->string('phone', 256)->nullable();
            $table->unsignedBigInteger('priorityId')->nullable();
            $table->unsignedBigInteger('qbClassId')->nullable();
            $table->boolean('residentialFlag')->default(false);
            $table->integer('revisionNum')->nullable();
            $table->string('salesman', 30)->nullable();
            $table->unsignedBigInteger('salesmanId')->nullable();
            $table->string('salesmanInitials', 5)->nullable();
            $table->unsignedBigInteger('shipTermsId')->nullable();
            $table->string('shipToAddress', 90)->nullable();
            $table->string('shipToCity', 30)->nullable();
            $table->unsignedBigInteger('shipToCountryId')->nullable();
            $table->string('shipToName', 41)->nullable();
            $table->unsignedBigInteger('shipToStateId')->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->unsignedBigInteger('statusId')->nullable();
            $table->double('taxRate')->nullable();
            $table->unsignedBigInteger('taxRateId')->nullable();
            $table->string('taxRateName', 31)->nullable();
            $table->boolean('toBeEmailed')->default(false);
            $table->boolean('toBePrinted')->default(false);
            $table->boolean('totalIncludesTax')->default(false);
            $table->decimal('totalTax', 28, 9)->nullable();
            $table->decimal('subTotal', 28, 9)->nullable();
            $table->decimal('totalPrice', 28, 9)->nullable();
            $table->unsignedBigInteger('typeId')->nullable();
            $table->string('url', 256)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('vendorPO', 25)->nullable();
            $table->string('customField')->nullable();
            $table->timestamps();
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
