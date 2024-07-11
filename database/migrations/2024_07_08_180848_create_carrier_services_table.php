<?php

use App\Models\Carrier;
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
        Schema::create('carrierservice', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(false);
            $table->foreignId('carrierId')->constrained('carrier')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->boolean('readOnly')->default(false);
            $table->index('carrierId', 'Performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_services');
    }
};
