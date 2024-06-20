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
        Schema::create('customfield', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('accessRight');
            $table->boolean('activeFlag');
            $table->integer('customFieldTypeId');
            $table->string('description', 256)->nullable();
            $table->integer('listId')->nullable();
            $table->string('name', 41)->unique('u_name');
            $table->boolean('required');
            $table->integer('sortOrder');
            $table->integer('tableId')->unique('u_tableid');

            $table->index(['listId', 'tableId', 'customFieldTypeId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customfield');
    }
};
