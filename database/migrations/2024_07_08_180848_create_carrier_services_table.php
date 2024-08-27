<?php

use App\Models\Carrier;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carrierservice', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(true);
            $table->foreignId('carrierId')->nullable()->constrained('carrier');
            $table->string('code')->nullable();
            $table->string('name');
            $table->boolean('readOnly')->default(false);
            $table->index('carrierId', 'Performance');
        });

        DB::table('carrierservice')->insert([
            ['carrierId' => 3, 'code' => '01', 'name' =>  'Next Day Air',],
            ['carrierId' => 3, 'code' => '02', 'name' =>  '2nd Day Air ',],
            ['carrierId' => 3, 'code' => '03', 'name' =>  'Ground',],
            ['carrierId' => 3, 'code' => '12', 'name' =>  '3 Day Select',],
            ['carrierId' => 3, 'code' => '13', 'name' =>  'Next Day Air Saver',],
            ['carrierId' => 3, 'code' => '14', 'name' =>  'Next Day Air Early A.M.',],
            ['carrierId' => 3, 'code' => '59', 'name' =>  '2nd Day Air A.M.',],
            ['carrierId' => 4, 'code' => '01', 'name' =>  'Ground',],
            ['carrierId' => 4, 'code' => '02', 'name' =>  'First Overnight',],
            ['carrierId' => 4, 'code' => '03', 'name' =>  'Standard Overnight',],
            ['carrierId' => 4, 'code' => '04', 'name' =>  '2Day',],
            ['carrierId' => 4, 'code' => '05', 'name' =>  'Express Saver',],
            ['carrierId' => 4, 'code' => '06', 'name' =>  'SmartPost',],
            ['carrierId' => 4, 'code' => '08', 'name' =>  'International Priority',],
            ['carrierId' => 4, 'code' => '09', 'name' =>  'International Ground',],
            ['carrierId' => 5, 'code' => '', 'name' =>  'Priority Mall',],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_services');
    }
};
