<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateXoitemstatusTable extends Migration
{
    public function up()
    {
        Schema::create('xoitemstatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('xoitemstatus')->insert([
            ['id' => 70, 'name' => 'Closed Short'],
            ['id' => 10, 'name' => 'Entered'],
            ['id' => 60, 'name' => 'Fullifield'],
            ['id' => 30, 'name' => 'Patrial'],
            ['id' => 40, 'name' => 'Picked'],
            ['id' => 20, 'name' => 'Picking'],
            ['id' => 50, 'name' => 'Shipped'],
            ['id' => 80, 'name' => 'Void'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('xoitemstatus');
    }
}
