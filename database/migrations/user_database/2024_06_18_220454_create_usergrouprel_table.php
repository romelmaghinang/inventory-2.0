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
        Schema::create('usergrouprel', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('groupId')->index('performance');
            $table->integer('userId')->unique('u_userid');

            $table->unique(['groupId'], 'u_groupid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usergrouprel');
    }
};
