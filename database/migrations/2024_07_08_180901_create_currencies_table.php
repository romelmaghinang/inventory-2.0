<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(true);
            $table->string('code', 3)->nullable();
            $table->dateTime('dateCreated')->nullable();
            $table->dateTime('dateLastModified')->nullable();
            $table->boolean('excludeFromUpdate')->nullable();
            $table->boolean('homeCurrency')->nullable();
            $table->unsignedBigInteger('lastChangedUserId')->nullable();
            $table->string('name', 255)->nullable()->unique();
            $table->integer('rate')->nullable();
            $table->integer('symbol')->nullable();
            $table->index('lastChangedUserId', 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
