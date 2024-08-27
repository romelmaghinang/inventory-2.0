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
            $table->id();
            $table->string('billToAddress', 90)->nullable();
            $table->string('billToCity', 30)->nullable();
            $table->string('billToName', 41)->nullable();
            $table->string('billToZip', 10)->nullable();
            $table->decimal('cost', 28, 9)->nullable();
            $table->double('currencyRate')->nullable();
            $table->string('customerContact', 30)->nullable();
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
            $table->decimal('mcTotalTax', 28, 9)->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 25)->nullable();
            $table->string('phone', 256)->nullable();
            $table->boolean('residentialFlag')->default(false);
            $table->integer('revisionNum')->nullable();
            $table->string('salesman', 30)->nullable();
            $table->unsignedBigInteger('salesmanId')->nullable();
            $table->string('salesmanInitials', 5)->nullable();
            $table->string('shipToAddress', 90)->nullable();
            $table->string('shipToCity', 30)->nullable();
            $table->string('shipToName', 41)->nullable();
            $table->string('shipToZip', 10)->nullable();
            $table->double('taxRate')->nullable();
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

            $table->foreignId('billToCountryId')->nullable()->constrained('country');
            $table->foreignId('billToStateId')->nullable()->constrained('state');
            $table->foreignId('carrierId')->nullable()->constrained('carrier');
            $table->foreignId('carrierServiceId')->nullable()->constrained('carrierservice');
            $table->foreignId('currencyId')->nullable()->constrained('currency');
            $table->foreignId('customerId')->nullable()->constrained('customer');
            $table->foreignId('locationGroupId')->nullable()->constrained('locationgroup');
            $table->foreignId('paymentTermsId')->nullable()->constrained('paymentterms');
            $table->foreignId('shipTermsId')->nullable()->constrained('shipterms');
            $table->foreignId('priorityId')->nullable()->constrained('priority');
            $table->foreignId('qbClassId')->nullable()->constrained('qbclass');
            $table->foreignId('shipToCountryId')->nullable()->constrained('country');
            $table->foreignId('shipToStateId')->nullable()->constrained('state');
            $table->foreignId('statusId')->nullable()->constrained('sostatus');
            $table->foreignId('taxRateId')->nullable()->constrained('taxrate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
