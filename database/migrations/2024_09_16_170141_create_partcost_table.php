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
        Schema::create('partcost', function (Blueprint $table) {
            $table->id(); // Auto incrementing primary key
            $table->decimal('avgCost', 28, 9); // Average cost with high precision
            $table->dateTime('dateCreated')->useCurrent();
            $table->dateTime('dateLastModified')->nullable(); // Nullable last modified date
            $table->decimal('qty', 28, 9); // Quantity with high precision
            $table->decimal('totalCost', 28, 9); // Total cost = avgCost * qty

            $table->primary('id'); // Primary key
            $table->foreignId('partId')->constrained('part'); // Unique constraint for partId
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partcost');
    }
};
