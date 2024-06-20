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
        Schema::create('defaultlocation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('locationGroupId')->unique('u_locationgroupid');
            $table->integer('locationId')->unique('u_locationid');
            $table->integer('partId');

            $table->index(['locationGroupId', 'locationId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defaultlocation');
    }
};
