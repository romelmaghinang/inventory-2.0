<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateXoitemtypeTable extends Migration
{
    public function up()
    {
        Schema::create('xoitemtype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('xoitemtype')->insert([
            ['id' => 20, 'name' => 'Receive'],
            ['id' => 10, 'name' => 'Send'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('xoitemtype');
    }
}
