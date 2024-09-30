<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoitemstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soitemstatus', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
        });

        DB::table('soitemstatus')->insert([
            ['id' => 11, 'name' => 'Awaiting Build'],
            ['id' => 12, 'name' => 'Building'],
            ['id' => 14, 'name' => 'Built'],
            ['id' => 75, 'name' => 'Cancelled'],
            ['id' => 60, 'name' => 'Closed Short'],
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 50, 'name' => 'Fullfilled'],
            ['id' => 95, 'name' => 'Historical'],
            ['id' => 30, 'name' => 'Partial'],
            ['id' => 40, 'name' => 'Picked'],
            ['id' => 20, 'name' => 'Picking'],
            ['id' => 70, 'name' => 'Voided'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soitemstatus');
    }
}
