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
        Schema::create('trackinginfo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('info', 41)->nullable();
            $table->dateTime('infoDate')->nullable();
            $table->double('infoDouble')->nullable();
            $table->integer('infoInteger')->nullable();
            $table->integer('partTrackingId');
            $table->decimal('qty', 28, 9)->nullable();
            $table->integer('recordId')->nullable();
            $table->integer('tableId')->nullable();

            $table->index(['partTrackingId', 'infoDate', 'info', 'infoInteger', 'qty', 'recordId', 'tableId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackinginfo');
    }
};
