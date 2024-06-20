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
        Schema::create('itemadjust', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('activeFlag');
            $table->decimal('amount', 28, 9)->nullable();
            $table->string('description', 256)->nullable();
            $table->integer('expenseAsAccountId')->nullable();
            $table->integer('incomeAsAccountId')->nullable();
            $table->string('name', 31)->unique('u_name');
            $table->decimal('percentage', 28, 9)->nullable();
            $table->integer('taxRateId')->nullable();
            $table->boolean('taxableFlag');
            $table->integer('typeId');

            $table->index(['typeId', 'taxRateId', 'expenseAsAccountId', 'incomeAsAccountId', 'name'], 'performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemadjust');
    }
};
