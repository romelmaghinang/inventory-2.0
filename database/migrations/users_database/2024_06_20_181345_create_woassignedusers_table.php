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
        Schema::create('woassignedusers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userId')->index('performance');
            $table->integer('woId')->unique('u_woid');

            $table->unique(['userId'], 'u_userid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woassignedusers');
    }
};
