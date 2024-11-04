<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePotypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('potype')->insert([
            ['id' => 20, 'name' => 'Drop Ship'],
            ['id' => 10, 'name' => 'Standard'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potype');
    }
}
