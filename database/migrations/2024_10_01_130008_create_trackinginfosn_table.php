<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingInfoSnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackinginfosn', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partTrackingId');
            $table->string('serialNum');
            $table->unsignedBigInteger('trackingInfoId');

            $table->foreign('partTrackingId')->references('id')->on('parttracking')->onDelete('cascade');
            $table->foreign('trackingInfoId')->references('id')->on('trackinginfo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackinginfosn');
    }
}
