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
        Schema::create('woitem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('cost', 28, 9)->nullable();
            $table->string('description', 256)->nullable();
            $table->integer('moItemId');
            $table->integer('partId')->nullable();
            $table->decimal('qtyScrapped', 28, 9)->nullable();
            $table->decimal('qtyTarget', 28, 9)->nullable();
            $table->decimal('qtyUsed', 28, 9)->nullable();
            $table->integer('sortId');
            $table->integer('typeId')->nullable();
            $table->integer('uomId')->nullable();
            $table->integer('woId');

            $table->index(['typeId', 'partId', 'moItemId', 'uomId', 'woId'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woitem');
    }
};
