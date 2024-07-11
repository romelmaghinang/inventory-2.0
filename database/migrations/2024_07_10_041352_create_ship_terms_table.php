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
        Schema::create('shipterms', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(0);
            $table->string('name', 32)->nullable()->unique('u_name');
            $table->boolean('readOnly')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_terms');
    }
};
