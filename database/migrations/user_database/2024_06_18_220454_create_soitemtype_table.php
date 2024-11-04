<?php

use App\Enums\Enum\SalesOrderItemTypeEnum;
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
        Schema::create('soitemtype', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
        });

        DB::table('soitemtype')->insert([
            ['id' => 10, 'name' => 'Sale'],
            ['id' => 11, 'name' => 'Misc. Sale'],
            ['id' => 12, 'name' => 'Drop Ship'],
            ['id' => 20, 'name' => 'Credit Return'],
            ['id' => 21, 'name' => 'Misc. Credit'],
            ['id' => 30, 'name' => 'Discount Percentage'],
            ['id' => 31, 'name' => 'Discount Amount'],
            ['id' => 40, 'name' => 'Subtotal'],
            ['id' => 50, 'name' => 'Assoc. Price'],
            ['id' => 60, 'name' => 'Shipping'],
            ['id' => 70, 'name' => 'Tax'],
            ['id' => 80, 'name' => 'Kit'],
            ['id' => 90, 'name' => 'Note'],
            ['id' => 100, 'name' => 'BOM Configuration Item'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_item_types');
    }
};
