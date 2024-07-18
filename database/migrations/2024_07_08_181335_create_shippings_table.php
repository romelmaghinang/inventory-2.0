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
            $table->integer('carrierId');
            $table->integer('carrierServiceId')->nullable();
            $table->integer('cartonCount')->nullable();
            $table->string('contact', 250)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->dateTime('dateShipped')->nullable();
            $table->integer('locationGroupId');
            $table->longText('note')->nullable();
            $table->string('num', 35)->nullable();
            $table->integer('orderTypeId');
            $table->boolean('ownerIsFrom');
            $table->integer('poId')->nullable();
            $table->integer('shipToId')->nullable();
            $table->string('shipmentIdentificationNumber', 32)->nullable();
            $table->integer('shippedBy')->nullable();
            $table->integer('soId')->nullable();
            $table->integer('statusId')->nullable();
            $table->integer('xoId')->nullable();
            $table->unique('num');
            $table->index(['shippedBy', 'carrierId', 'locationGroupId', 'orderTypeId', 'statusId', 'FOBPointId', 'carrierServiceId', 'soId', 'xoId', 'poId', 'dateShipped'], 'Performance');
            $table->timestamps();
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
