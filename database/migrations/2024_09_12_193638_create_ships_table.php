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
        Schema::create('ship', function (Blueprint $table) {
            $table->id();
            $table->integer('FOBPointId')->nullable();
            $table->string('billOfLading', 20)->nullable();
            $table->integer('cartonCount')->nullable();
            $table->string('contact', 250)->nullable();
            $table->dateTime('dateShipped')->nullable();
            $table->longText('note')->nullable();
            $table->string('num', 35)->nullable()->unique();
            $table->integer('orderTypeId')->nullable();
            $table->boolean('ownerIsFrom')->nullable();
            $table->integer('poId')->nullable();
            $table->integer('shipToId')->nullable();
            $table->string('shipmentIdentificationNumber', 32)->nullable();
            $table->integer('shippedBy')->nullable();
            $table->integer('statusId')->nullable();
            $table->integer('xoId')->nullable();

            $table->foreignId('carrierId')->constrained('carrier');
            $table->foreignId('carrierServiceId')->nullable()->constrained('carrierservice');
            $table->foreignId('locationGroupId')->constrained('locationgroup');
            $table->foreignId('soId')->nullable()->constrained('so');

            $table->index([
                'shippedBy',
                'carrierId',
                'locationGroupId',
                'orderTypeId',
                'statusId',
                'FOBPointId',
                'carrierServiceId',
                'soId',
                'xoId',
                'poId',
                'dateShipped'
            ], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship');
    }
};
