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
        Schema::create('customerparts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customerId')->unique('u_customerid');
            $table->string('customerPartNumber', 71);
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateLastPurchased')->nullable();
            $table->integer('lastChangedUserId')->nullable();
            $table->decimal('lastPrice', 28, 9)->nullable();
            $table->integer('productId')->nullable()->unique('u_productid');

            $table->index(['customerPartNumber', 'lastChangedUserId', 'customerId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerparts');
    }
};
