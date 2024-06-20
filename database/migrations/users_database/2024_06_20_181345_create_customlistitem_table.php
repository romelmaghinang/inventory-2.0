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
        Schema::create('customlistitem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description', 256)->nullable();
            $table->integer('listId')->nullable()->index('performance');
            $table->string('name', 41)->unique('u_name');

            $table->unique(['listId'], 'u_listid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customlistitem');
    }
};
