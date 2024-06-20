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
        Schema::create('useraccess', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('groupId');
            $table->boolean('modifyFlag');
            $table->string('moduleName', 256);
            $table->boolean('viewFlag');

            $table->index(['groupId', 'moduleName'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('useraccess');
    }
};
