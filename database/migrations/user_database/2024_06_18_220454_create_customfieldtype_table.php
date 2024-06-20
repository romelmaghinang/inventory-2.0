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
        Schema::create('customfieldtype', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description', 256)->nullable();
            $table->string('name', 41)->index('performance');
            $table->integer('tableId')->unique('u_tableid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customfieldtype');
    }
};
