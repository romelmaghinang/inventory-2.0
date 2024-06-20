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
        Schema::create('usertolg', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('defaultFlag');
            $table->integer('locationGroupId')->nullable()->index('performance');
            $table->integer('userId')->nullable()->unique('u_userid');

            $table->unique(['locationGroupId'], 'u_locationgroupid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertolg');
    }
};
