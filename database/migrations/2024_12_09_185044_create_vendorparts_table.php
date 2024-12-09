<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorpartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendorparts', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('defaultFlag')->default(false);
            $table->decimal('lastCost', 28, 9)->nullable();
            $table->dateTime('lastDate')->nullable();
            $table->integer('leadTime')->nullable();
            
            $table->unsignedBigInteger('partId');
            $table->unsignedBigInteger('uomId');
            $table->unsignedBigInteger('userId')->nullable();
            $table->unsignedBigInteger('vendorId');
    
            $table->decimal('qtyMax', 28, 9)->nullable();
            $table->decimal('qtyMin', 28, 9)->nullable();
            $table->string('vendorPartNumber', 70)->nullable();
            $table->timestamps(); 
    
            $table->foreign('partId')->references('id')->on('part')->onDelete('cascade');
            $table->foreign('uomId')->references('id')->on('uom')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendorId')->references('id')->on('vendor')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendorparts');
    }
}
