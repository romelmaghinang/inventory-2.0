<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paymenttermstype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
        });

        DB::table('paymenttermstype')->insert(
            [
                ['id' => 40, 'name' => 'CCD'],
                ['id' => 30, 'name' => 'CIA'],
                ['id' => 20, 'name' => 'COD'],
                ['id' => 60, 'name' => 'MONTH'],
                ['id' => 10, 'name' => 'NET'],
                ['id' => 50, 'name' => 'NONE'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_terms_types');
    }
};
