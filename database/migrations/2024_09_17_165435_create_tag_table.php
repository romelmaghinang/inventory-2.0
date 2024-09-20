<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tag')) {
        Schema::create('tag', function (Blueprint $table) {
            $table->id(); // Auto incrementing primary key
            $table->dateTime('dateCreated')->useCurrent(); // Automatically set to the current timestamp
            $table->dateTime('dateLastCycleCount')->nullable(); // Nullable field for last cycle count
            $table->dateTime('dateLastModified')->useCurrent(); // Automatically set to the current timestamp
            $table->integer('locationId')->notNullable(); // Location ID (related to location table)
            $table->integer('num')->nullable()->unique(); // Unique 'num' field
            $table->integer('partId')->nullable(); // Part ID, nullable
            $table->decimal('qty', 28, 9)->nullable(); // Quantity with high precision
            $table->decimal('qtyCommitted', 28, 9)->nullable(); // Committed quantity with high precision
            $table->boolean('serializedFlag'); // Boolean flag for serialized items
            $table->string('trackingEncoding', 30); // Tracking encoding (varchar 30)
            $table->integer('typeId')->default(30); // Default typeId set to 30
            $table->boolean('usedFlag'); // Boolean flag for used items
            $table->integer('woItemId')->nullable(); // Nullable field for work order item ID

            // Defining primary and unique keys
            $table->primary('id');
            $table->unique('num');
            
            // Defining performance indexes
            $table->index(['locationId', 'woItemId', 'partId', 'typeId', 'dateLastCycleCount', 'num'], 'Performance');
        });
    } 
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(`testdb`.`tag`);
    }
};
