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
        Schema::create('taxratetype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->nullable(false);
            $table->timestamps();
        });

        DB::table('taxratetype')->insert(
            [
                ['name' => 'Percentage'],
                ['name' => 'Flat Rate'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxratetype');
    }
};
