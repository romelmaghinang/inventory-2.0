<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiptitemsstatus', function (Blueprint $table) {
            $table->id(); 
            $table->string('name', 50); 
            $table->timestamps();
        });

        DB::table('receiptitemsstatus')->insert([
            ['name' => 'Entered'],
            ['name' => 'Fulfilled'],
            ['name' => 'Received'],
            ['name' => 'Reconciled'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receiptitemsstatus');
    }
};
