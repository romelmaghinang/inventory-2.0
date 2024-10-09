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
        Schema::create('receipttype', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 30)->unique('u_name');
        });

        DB::table('receipttype')->insert([
            ['id' => 20, 'name' => 'Receive Only'],
            ['id' => 30, 'name' => 'Reconcile Only'],
            ['id' => 10, 'name' => 'Standard'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipttype');
    }
};
