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
        Schema::create('parttracking', function (Blueprint $table) {
            $table->id();
            $table->string('abbr', 41);
            $table->boolean('activeFlag')->default(true);
            $table->string('description', 256)->nullable();
            $table->string('name', 41)->unique();
            $table->integer('sortOrder')->nullable();

            $table->unsignedBigInteger('typeId');
            $table->foreign('typeId')->references('id')->on('parttrackingtype')->onDelete('cascade');

            $table->index('typeId', 'Performance');
        });

        DB::table('parttracking')->insert([
            ['abbr' => 'Lot#', 'name' => 'Lot Number', 'sortOrder' => 1, 'typeId' => 10],
            ['abbr' => 'Rev#', 'name' => 'Revision Level', 'sortOrder' => 2, 'typeId' => 10], 
            ['abbr' => 'ExpDate', 'name' => 'Expiration Date', 'sortOrder' => 3, 'typeId' => 30], 
            ['abbr' => 'SN(s)', 'name' => 'Serial Number', 'sortOrder' => 4, 'typeId' => 40],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parttracking');
    }
};
