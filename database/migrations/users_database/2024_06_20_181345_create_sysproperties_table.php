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
        Schema::create('sysproperties', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('dateLastModified')->nullable();
            $table->string('owner', 30)->nullable();
            $table->boolean('readAllowed');
            $table->string('sysKey', 30)->index('performance');
            $table->string('sysValue', 1024);
            $table->boolean('writeAllowed');

            $table->unique(['sysKey'], 'u_syskey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysproperties');
    }
};
