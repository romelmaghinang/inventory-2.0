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
        Schema::create('tablereference', function (Blueprint $table) {
            $table->id();
            $table->string('className', 100)->nullable();
            $table->integer('tableId');
            $table->string('tableRefName', 30);
            $table->index('tableId', 'Performance');
        });

        DB::table('tablereference')->insert([
            ['id' => 1, 'tableId' => 245157248, 'tableRefName' => 'AccountGroup', 'className' => null, ],
            ['id' => 2, 'tableId' => 1705436292, 'tableRefName' => 'BOM', 'className' => 'Bill of Materials',],
            ['id' => 3, 'tableId' => 322343754, 'tableRefName' => 'BOMItem', 'className' => null,],
            ['id' => 4, 'tableId' => 1975209460, 'tableRefName' => 'Carrier', 'className' => 'Carrier', ],
            ['id' => 5, 'tableId' => 1682600224, 'tableRefName' => 'Category', 'className' => null, ],
            ['id' => 6, 'tableId' => -1168447834, 'tableRefName' => 'CategoryGroup', 'className' => null, ],
            ['id' => 7, 'tableId' => -776196226, 'tableRefName' => 'Company', 'className' => 'Company',],
            ['id' => 8, 'tableId' => 2142022944, 'tableRefName' => 'Customer', 'className' => 'Customer',],
            ['id' => 9, 'tableId' => -1081854656, 'tableRefName' => 'InventoryLog', 'className' => null, ],
            ['id' => 10, 'tableId' => -1559972096, 'tableRefName' => 'Location', 'className' => null, ],
            ['id' => 11, 'tableId' => -1613206600, 'tableRefName' => 'LocationGroup', 'className' => null, ],
            ['id' => 12, 'tableId' => -217859456, 'tableRefName' => 'MO', 'className' => 'Manufacture Order',],
            ['id' => 13, 'tableId' => 891690304, 'tableRefName' => 'MOItem', 'className' => null, ],
            ['id' => 14, 'tableId' => 397076832, 'tableRefName' => 'PO', 'className' => 'Purchase Order',],
            ['id' => 15, 'tableId' => -1200586080, 'tableRefName' => 'POItem', 'className' => 'Purchase Order Line Item',],
            ['id' => 16, 'tableId' => -2003201472, 'tableRefName' => 'Part', 'className' => 'Part',],
            ['id' => 17, 'tableId' => -195330016, 'tableRefName' => 'PartCategory', 'className' => null, ],
            ['id' => 18, 'tableId' => 1056061312, 'tableRefName' => 'PartCost', 'className' => null, ],
            ['id' => 19, 'tableId' => -731498336, 'tableRefName' => 'PartToTree', 'className' => null, ],
            ['id' => 20, 'tableId' => 589007520, 'tableRefName' => 'PartTree', 'className' => null, ],
            ['id' => 21, 'tableId' => -910618880, 'tableRefName' => 'Pick', 'className' => 'Pick',],
            ['id' => 22, 'tableId' => -1515431424, 'tableRefName' => 'PickItem', 'className' => null, ],
            ['id' => 23, 'tableId' => -1407594912, 'tableRefName' => 'Post', 'className' => null, ],
            ['id' => 24, 'tableId' => -883889664, 'tableRefName' => 'PostPO', 'className' => null, ],
            ['id' => 25, 'tableId' => -1311550272, 'tableRefName' => 'PostPOItem', 'className' => null, ],
            ['id' => 26, 'tableId' => 1264910176, 'tableRefName' => 'PostSO', 'className' => null, ],
            ['id' => 27, 'tableId' => 41689248, 'tableRefName' => 'PostSOItem', 'className' => null, ],
            ['id' => 28, 'tableId' => 1982931712, 'tableRefName' => 'PostXO', 'className' => null, ],
            ['id' => 29, 'tableId' => -1997878848, 'tableRefName' => 'PostXOItem', 'className' => null, ],
            ['id' => 30, 'tableId' => 97022306, 'tableRefName' => 'Product', 'className' => 'Product',],
            ['id' => 31, 'tableId' => -24755884, 'tableRefName' => 'ProductToTree', 'className' => null, ],
            ['id' => 32, 'tableId' => -1326267074, 'tableRefName' => 'ProductTree', 'className' => null, ],
            ['id' => 33, 'tableId' => 1424804368, 'tableRefName' => 'RMA', 'className' => 'RMA',],
            ['id' => 34, 'tableId' => -183091242, 'tableRefName' => 'RMAItem', 'className' => null, ],
            ['id' => 35, 'tableId' => -2001398668, 'tableRefName' => 'Receipt', 'className' => null, ],
            ['id' => 36, 'tableId' => 1259138490, 'tableRefName' => 'ReceiptItem', 'className' => null, ],
            ['id' => 37, 'tableId' => -1855906806, 'tableRefName' => 'POSTransaction', 'className' => null, ],
            ['id' => 38, 'tableId' => 1012013120, 'tableRefName' => 'SO', 'className' => 'Sales Order',],
            ['id' => 39, 'tableId' => 1002104832, 'tableRefName' => 'SOItem', 'className' => 'Sales Order Line Item',],
            ['id' => 40, 'tableId' => -2018340896, 'tableRefName' => 'Ship', 'className' => 'Ship',],
            ['id' => 41, 'tableId' => 1555030112, 'tableRefName' => 'ShipItem', 'className' => null, ],
            ['id' => 42, 'tableId' => -104438700, 'tableRefName' => 'SysUser', 'className' => 'User',],
            ['id' => 43, 'tableId' => 258481720, 'tableRefName' => 'Tag', 'className' => null, ],
            ['id' => 44, 'tableId' => -1426569332, 'tableRefName' => 'UserGroup', 'className' => 'User Group', ],
            ['id' => 45, 'tableId' => -201831487, 'tableRefName' => 'UserToLG', 'className' => null, ],
            ['id' => 46, 'tableId' => -850561568, 'tableRefName' => 'Vendor', 'className' => 'Vendor',],
            ['id' => 47, 'tableId' => -1031383360, 'tableRefName' => 'WO', 'className' => 'Work Order',],
            ['id' => 48, 'tableId' => -355941248, 'tableRefName' => 'WOItem', 'className' => null, ],
            ['id' => 49, 'tableId' => 1280345280, 'tableRefName' => 'WOPickDetail', 'className' => null, ],
            ['id' => 50, 'tableId' => 605251168, 'tableRefName' => 'XO', 'className' => 'Transfer Order',],
            ['id' => 51, 'tableId' => 378289056, 'tableRefName' => 'XOItem', 'className' => null, ],
            ['id' => 52, 'tableId' => 40400710, 'tableRefName' => 'BOMInstructionItem', 'className' => 'Bill of Materials Instruction',],
            ['id' => 53, 'tableId' => -123456789, 'tableRefName' => 'WOInstruction', 'className' => null, ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tablereference');
    }
};
