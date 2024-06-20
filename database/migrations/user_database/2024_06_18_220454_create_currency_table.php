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
        Schema::create('currency', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->string('code', 3)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('excludeFromUpdate')->nullable();
            $table->boolean('homeCurrency')->nullable();
            $table->integer('lastChangedUserId')->index('performance');
            $table->string('name')->nullable()->unique('u_name');
            $table->integer('rate')->nullable();
            $table->integer('symbol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
