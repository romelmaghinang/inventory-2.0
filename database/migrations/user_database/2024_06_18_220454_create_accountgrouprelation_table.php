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
        Schema::create('accountgrouprelation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accountId')->nullable();
            $table->integer('groupId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountgrouprelation');
    }
};
