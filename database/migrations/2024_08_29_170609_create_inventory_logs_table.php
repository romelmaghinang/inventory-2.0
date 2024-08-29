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
        Schema::create('inventorylog', function (Blueprint $table) {
            $table->id();
            $table->integer('begLocationId');
            $table->bigInteger('begTagNum')->nullable();
            $table->decimal('changeQty', 28, 9)->nullable();
            $table->decimal('cost', 28, 9)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->integer('endLocationId');
            $table->bigInteger('endTagNum')->nullable();
            $table->dateTime('eventDate')->nullable();
            $table->string('info', 100)->nullable();
            $table->integer('locationGroupId')->nullable();
            $table->integer('partId');
            $table->integer('partTrackingId')->nullable();
            $table->decimal('qtyOnHand', 28, 9)->nullable();
            $table->bigInteger('recordId')->nullable();
            $table->integer('tableId')->nullable();
            $table->integer('typeId')->nullable();
            $table->integer('userId')->nullable();

            $table->index(['partId', 'typeId', 'endLocationId', 'begLocationId', 'userId', 'locationGroupId', 'begTagNum', 'endTagNum', 'eventDate', 'partTrackingId', 'recordId', 'tableId'], 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventorylog');
    }
};
