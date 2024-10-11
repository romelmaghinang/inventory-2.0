<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptitemsstatusTable extends Migration
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
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 40, 'name' => 'Fullfiled'],
            ['id' => 30, 'name' => 'Received'],
            ['id' => 20, 'name' => 'Reconciled'],
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
}
