<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postatus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('postatus')->insert([
            ['id' => 10, 'name' => 'Bad Request'],
            ['id' => 70, 'name' => 'Closed Short'],
            ['id' => 2, 'name' => 'For Calendar'],
            ['id' => 60, 'name' => 'Fulfilled'],
            ['id' => 95, 'name' => 'Historical'],
            ['id' => 20, 'name' => 'Issued'],
            ['id' => 40, 'name' => 'Partial'],
            ['id' => 15, 'name' => 'Pending Approval'],
            ['id' => 50, 'name' => 'Pricked'],
            ['id' => 30, 'name' => 'Picking'],
            ['id' => 55, 'name' => 'Shipped'],
            ['id' => 80, 'name' => 'Void'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postatus');
    }
}
