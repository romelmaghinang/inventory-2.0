<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateXotypeTable extends Migration
{
    public function up()
    {
        Schema::create('xotype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('xotype')->insert([
            ['id' => 20, 'name' => 'Moving'],
            ['id' => 30, 'name' => 'Putaway'],
            ['id' => 10, 'name' => 'Shipping'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('xotype');
    }
}
