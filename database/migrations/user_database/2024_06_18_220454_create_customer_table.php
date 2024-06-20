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
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accountId');
            $table->string('accountingHash', 30)->nullable();
            $table->string('accountingId', 36)->nullable();
            $table->boolean('activeFlag')->nullable();
            $table->decimal('creditLimit', 28, 9)->nullable();
            $table->integer('currencyId')->nullable();
            $table->decimal('currencyRate', 28, 9)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultCarrierId')->nullable();
            $table->integer('defaultPaymentTermsId')->nullable();
            $table->integer('defaultSalesmanId');
            $table->integer('defaultShipTermsId')->nullable();
            $table->integer('jobDepth')->nullable();
            $table->string('lastChangedUser', 15)->nullable();
            $table->string('name', 41)->unique('u_name');
            $table->string('note', 90)->nullable();
            $table->string('number', 30)->nullable()->unique('u_number');
            $table->integer('parentId')->nullable()->unique('u_parentid');
            $table->integer('pipelineAccountNum')->nullable();
            $table->integer('qbClassId')->nullable();
            $table->integer('statusId');
            $table->integer('sysUserId')->nullable();
            $table->boolean('taxExempt');
            $table->string('taxExemptNumber', 30)->nullable();
            $table->integer('taxRateId')->nullable();
            $table->boolean('toBeEmailed');
            $table->boolean('toBePrinted');
            $table->string('url', 30)->nullable();
            $table->integer('issuableStatusId')->nullable();
            $table->integer('carrierServiceId')->nullable();

            $table->index(['name', 'number', 'carrierServiceId', 'accountId', 'statusId', 'taxRateId', 'defaultPaymentTermsId', 'parentId', 'qbClassId', 'defaultShipTermsId', 'currencyId', 'defaultCarrierId', 'issuableStatusId'], 'performance');
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
