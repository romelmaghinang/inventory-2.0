<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInventoryViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW qtyinventory AS
            SELECT 
                partId AS PARTID,
                locationGroupId AS LOCATIONGROUPID,
                qtyOnHand AS QTYONHAND
            FROM 
                InventoryLog;
        ");

        DB::statement("
            CREATE VIEW qtyinventorytotals AS
            SELECT 
                partId AS PARTID,
                locationGroupId AS LOCATIONGROUPID,
                SUM(qtyOnHand) AS QTYONHAND
            FROM 
                InventoryLog
            GROUP BY 
                partId, locationGroupId;
        ");

        DB::statement("
            CREATE VIEW qtynotavailabletopick AS
            SELECT 
                partId AS PARTID,
                locationGroupId AS LOCATIONGROUPID,
                SUM(qtyOnHand) AS QTY
            FROM 
                InventoryLog
            WHERE 
                qtyOnHand <= 0
            GROUP BY 
                partId, locationGroupId;
        ");

        DB::statement("
            CREATE VIEW qtyonhand AS
            SELECT 
                partId AS PARTID,
                locationGroupId AS LOCATIONGROUPID,
                SUM(qtyOnHand) AS QTY
            FROM 
                InventoryLog
            GROUP BY 
                partId, locationGroupId;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS qtyinventory;");
        DB::statement("DROP VIEW IF EXISTS qtyinventorytotals;");
        DB::statement("DROP VIEW IF EXISTS qtynotavailabletopick;");
        DB::statement("DROP VIEW IF EXISTS qtyonhand;");
    }
}
