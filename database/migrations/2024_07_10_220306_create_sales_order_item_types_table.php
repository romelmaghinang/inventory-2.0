<?php

use App\Enums\Enum\SalesOrderItemTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        $salesOrderData = [];

        foreach (SalesOrderItemTypeEnum::cases() as $case) [
            $salesOrderData[] =
                [
                    'name' => $case->value,
                ]
        ];

        DB::table('soitemtype')->insert($salesOrderData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_item_types');
    }
};
