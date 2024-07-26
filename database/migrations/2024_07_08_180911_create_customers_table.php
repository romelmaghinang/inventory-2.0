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
            $table->unsignedBigInteger('accountId')->nullable(false);
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag')->nullable();
            $table->decimal('creditLimit', 28, 9)->nullable();
            $table->unsignedBigInteger('currencyId')->nullable();
            $table->decimal('currencyRate', 28, 9)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->unsignedBigInteger('defaultCarrierId')->nullable();
            $table->unsignedBigInteger('defaultPaymentTermsId')->nullable();
            $table->unsignedBigInteger('defaultSalesmanId')->nullable();
            $table->unsignedBigInteger('defaultShipTermsId')->nullable();
            $table->integer('jobDepth')->nullable();
            $table->string('lastChangedUser', 15)->nullable();
            $table->string('name', 41)->nullable(false);
            $table->string('note', 90)->nullable();
            $table->string('number', 30)->nullable();
            $table->unsignedBigInteger('parentId')->nullable();
            $table->unsignedBigInteger('pipelineAccountNum')->nullable();
            $table->unsignedBigInteger('qbClassId')->nullable();
            $table->unsignedBigInteger('statusId')->nullable(false);
            $table->unsignedBigInteger('sysUserId')->nullable();
            $table->boolean('taxExempt')->nullable(false);
            $table->string('taxExemptNumber', 30)->nullable();
            $table->unsignedBigInteger('taxRateId')->nullable();
            $table->boolean('toBeEmailed')->nullable(false);
            $table->boolean('toBePrinted')->nullable(false);
            $table->string('url', 30)->nullable();
            $table->unsignedBigInteger('issuableStatusId')->nullable();
            $table->unsignedBigInteger('carrierServiceId')->nullable();
            $table->unique('name', 'u_name');
            $table->unique('number', 'u_number');
            $table->unique('parentId', 'u_parentId');
            $table->index(['name', 'number', 'carrierServiceId', 'accountId', 'statusId', 'taxRateId', 'defaultPaymentTermsId', 'parentId', 'qbClassId', 'defaultShipTermsId', 'currencyId', 'defaultCarrierId', 'issuableStatusId'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
