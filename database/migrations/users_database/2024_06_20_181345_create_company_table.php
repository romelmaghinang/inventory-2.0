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
        Schema::create('company', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('EANUCCPrefix', 30)->nullable();
            $table->string('abn', 25)->nullable();
            $table->integer('accountId');
            $table->dateTime('dateEntered')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->integer('defaultCarrierId');
            $table->boolean('defaultFlag')->nullable();
            $table->string('lastChangedUser', 15)->nullable();
            $table->string('name', 60)->unique('u_name');
            $table->boolean('taxExempt');
            $table->string('TAXEXEMPTNUMBER', 30)->nullable();

            $table->index(['defaultCarrierId', 'accountId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
