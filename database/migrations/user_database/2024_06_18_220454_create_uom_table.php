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
        Schema::create('uom', function (Blueprint $table) {
            $table->id();
            $table->boolean('activeFlag')->default(true);
            $table->string('code', 10)->unique();
            $table->boolean('defaultRecord')->default(true);
            $table->string('description', 256);
            $table->boolean('integral')->default(true);
            $table->string('name', 30)->unique();
            $table->boolean('readOnly');
            $table->foreignId('uomType')->constrained('uomtype');
        });

        DB::table('uom')->insert(
            [
                ['code' => 'ea', 'defaultRecord' => 1, 'description' => 'A single item.', 'integral' => 1, 'name' => 'Each', 'readOnly' => 1, 'uomType' => 1],
                ['code' => 'ft', 'defaultRecord' => 0, 'description' => 'English measurement in Feet.', 'integral' => 0, 'name' => 'Foot', 'readOnly' => 1, 'uomType' => 3],
                ['code' => 'lbs', 'defaultRecord' => 0, 'description' => 'American pound', 'integral' => 0, 'name' => 'Pound', 'readOnly' => 1, 'uomType' => 2],
                ['code' => 'hr', 'defaultRecord' => 0, 'description' => 'One hour.', 'integral' => 0, 'name' => 'Hour', 'readOnly' => 1, 'uomType' => 6],
                ['code' => 'gal', 'defaultRecord' => 0, 'description' => 'Basic US unit of liquid volume.', 'integral' => 0, 'name' => 'Gallon', 'readOnly' => 0, 'uomType' => 5],
                ['code' => 'floz', 'defaultRecord' => 0, 'description' => 'US unit of liquid volume.', 'integral' => 0, 'name' => 'Fluid Ounce', 'readOnly' => 0, 'uomType' => 5],
                ['code' => 'in', 'defaultRecord' => 0, 'description' => 'US unit of lenght.', 'integral' => 0, 'name' => 'Inch', 'readOnly' => 0, 'uomType' => 3],
                ['code' => 'kg', 'defaultRecord' => 0, 'description' => 'metric unit of weight.', 'integral' => 0, 'name' => 'Kilogram', 'readOnly' => 1, 'uomType' => 2],
                ['code' => 'oz', 'defaultRecord' => 0, 'description' => 'US unit weight.', 'integral' => 0, 'name' => 'Ounce', 'readOnly' => 1, 'uomType' => 2],
                ['code' => 'm', 'defaultRecord' => 0, 'description' => 'Basic metric unit of lenght.', 'integral' => 0, 'name' => 'Meter', 'readOnly' => 1, 'uomType' => 3],
                ['code' => 'L', 'defaultRecord' => 0, 'description' => 'Basic metric unit of liquid volume.', 'integral' => 0, 'name' => 'Liter', 'readOnly' => 0, 'uomType' => 5],
                ['code' => 'mm', 'defaultRecord' => 0, 'description' => '1/1000 of a meter.', 'integral' => 0, 'name' => 'Milimeter', 'readOnly' => 0, 'uomType' => 3],
                ['code' => 'cm', 'defaultRecord' => 0, 'description' => '1/100 of a meter.', 'integral' => 0, 'name' => 'Centimeter', 'readOnly' => 1, 'uomType' => 3],
                ['code' => 'km', 'defaultRecord' => 0, 'description' => '1000 meters.', 'integral' => 0, 'name' => 'Kilometer', 'readOnly' => 0, 'uomType' => 3],
                ['code' => 'g', 'defaultRecord' => 0, 'description' => 'Metric unit of weight.', 'integral' => 0, 'name' => 'Gram', 'readOnly' => 0, 'uomType' => 2],
                ['code' => 'mg', 'defaultRecord' => 0, 'description' => '1/1000 of a gram', 'integral' => 0, 'name' => 'Miligram', 'readOnly' => 0, 'uomType' => 2],
                ['code' => 'mL', 'defaultRecord' => 0, 'description' => '1/1000 of a Liter', 'integral' => 0, 'name' => 'Milililiter', 'readOnly' => 0, 'uomType' => 5],
                ['code' => 'pr', 'defaultRecord' => 0, 'description' => 'Two of one part', 'integral' => 0, 'name' => 'Pair', 'readOnly' => 0, 'uomType' => 1],
                ['code' => 'pk', 'defaultRecord' => 0, 'description' => 'Package of 20', 'integral' => 0, 'name' => 'Package', 'readOnly' => 0, 'uomType' => 1],
                ['code' => 'day', 'defaultRecord' => 0, 'description' => 'day', 'integral' => 0, 'name' => 'Day', 'readOnly' => 0, 'uomType' => 6],
                ['code' => 'ba', 'defaultRecord' => 0, 'description' => 'used for energy bar batches', 'integral' => 0, 'name' => 'batch', 'readOnly' => 0, 'uomType' => 1],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_of_measures');
    }
};
