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
        Schema::create('sostatus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->timestamps();
        });

        DB::table('sostatus')->insert([
            ['id' => 85 ,'name' => 'Cancelled'],
            ['id' => 70 ,'name' => 'Close Short'],
            ['id' => 10 ,'name' => 'Estimate'],
            ['id' => 90 ,'name' => 'Expired'],
            ['id' => 60 ,'name' => 'Fulfilled'],
            ['id' => 95 ,'name' => 'Historical'],
            ['id' => 25 ,'name' => 'In Progress'],
            ['id' => 20 ,'name' => 'Issued'],
            ['id' => 80 ,'name' => 'Voided'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sostatus');
    }
};
