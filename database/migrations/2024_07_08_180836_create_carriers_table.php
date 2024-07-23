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
        Schema::create('carrier', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(true);
            $table->string('description', 256)->nullable();
            $table->string('name', 60)->nullable()->unique('u_name');
            $table->boolean('readOnly')->nullable();
            $table->string('scac', 4)->nullable();
        });

        DB::table('carrier')->insert([
            ['name' => 'Delivery', 'description' => 'Deliver to Customer'],
            ['name' => 'Fedex', 'description' => 'Federal Express'],
            ['name' => 'UPS', 'description' => 'United Parcel Service'],
            ['name' => 'USPS', 'description' => 'United States Postal Service'],
            ['name' => 'Will Call', 'description' => 'Customer will Pickup'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier');
    }
};
