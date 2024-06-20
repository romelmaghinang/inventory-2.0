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
        Schema::create('pricingrule', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customerInclId')->nullable();
            $table->integer('customerInclTypeId');
            $table->boolean('dateApplies');
            $table->dateTime('dateBegin')->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateEnd')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('isActive');
            $table->boolean('isAutoApply');
            $table->boolean('isTier2');
            $table->string('name', 41)->unique('u_name');
            $table->decimal('paAmount', 28, 9)->nullable();
            $table->boolean('paApplies');
            $table->integer('paBaseAmountTypeId')->nullable();
            $table->decimal('paPercent', 28, 9)->nullable();
            $table->integer('paTypeId');
            $table->integer('productInclId')->nullable();
            $table->integer('productInclTypeId');
            $table->boolean('qtyApplies');
            $table->decimal('qtyMax', 28, 9)->nullable();
            $table->decimal('qtyMin', 28, 9)->nullable();
            $table->boolean('rndApplies');
            $table->boolean('rndIsMinus');
            $table->decimal('rndPMAmount', 28, 9)->nullable();
            $table->decimal('rndToAmount', 28, 9)->nullable();
            $table->integer('rndTypeId');
            $table->boolean('spcApplies');
            $table->integer('spcBuyX')->nullable();
            $table->integer('spcGetYFree')->nullable();
            $table->integer('userId');

            $table->index(['paTypeId', 'userId', 'productInclTypeId', 'rndTypeId', 'customerInclTypeId', 'paBaseAmountTypeId', 'productInclId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricingrule');
    }
};
