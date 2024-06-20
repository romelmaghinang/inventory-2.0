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
        Schema::create('sysuser', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->string('email')->nullable();
            $table->string('firstName', 15)->nullable();
            $table->string('initials', 5)->nullable();
            $table->string('lastName', 15)->nullable();
            $table->string('phone')->nullable();
            $table->string('userName', 30)->nullable()->unique('u_username');
            $table->string('userPwd', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysuser');
    }
};
