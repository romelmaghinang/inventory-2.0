<?php

use App\Models\CustomerStatus;
use App\Models\PaymentTerms;
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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accountId')->nullable();
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag')->nullable();
            $table->decimal('creditLimit', 28, 9)->nullable();
            $table->decimal('currencyRate', 28, 9)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->unsignedBigInteger('defaultSalesmanId')->nullable();
            $table->integer('jobDepth')->nullable();
            $table->string('lastChangedUser', 15)->nullable();
            $table->string('name', 41)->unique();
            $table->string('note', 90)->nullable();
            $table->string('number', 30)->nullable()->unique();
            $table->unsignedBigInteger('parentId')->nullable()->unique('u_parentId');
            $table->unsignedBigInteger('pipelineAccountNum')->nullable();
            $table->unsignedBigInteger('statusId')->nullable();
            $table->unsignedBigInteger('sysUserId')->nullable();
            $table->boolean('taxExempt')->default(true);
            $table->string('taxExemptNumber', 30)->nullable();
            $table->boolean('toBeEmailed')->default(true);
            $table->boolean('toBePrinted')->default(true);
            $table->string('url', 30)->nullable();
            $table->string('cf')->nullable();

            $table->foreignId('qbClassId')->nullable()->constrained('qbclass');
            $table->foreignId('defaultShipTermsId')->nullable()->constrained('shipterms');
            $table->foreignId('currencyId')->nullable()->constrained('currency');
            $table->foreignId('defaultPaymentTermsId')->nullable()->constrained('paymentterms');
            $table->foreignId('defaultCarrierId')->nullable()->constrained('carrier');
            $table->foreignId('issuableStatusId')->nullable()->constrained('customerstatus');
            $table->foreignId('carrierServiceId')->nullable()->constrained('carrierservice');
            $table->foreignId('taxRateId')->nullable()->constrained('taxrate');

            $table->index([
                'name', 'number', 'carrierServiceId', 'accountId', 'statusId', 'taxRateId',
                'defaultPaymentTermsId', 'parentId', 'qbClassId', 'defaultShipTermsId',
                'currencyId', 'defaultCarrierId', 'issuableStatusId'
            ], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
