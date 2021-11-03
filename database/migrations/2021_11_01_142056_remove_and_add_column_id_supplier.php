<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAndAddColumnIdSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `beli_header` DROP COLUMN `ID_SUPPLIER`");
        DB::statement("ALTER TABLE `beli_detail` ADD COLUMN `ID_SUPPLIER` INT(5) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `beli_header` ADD COLUMN `ID_SUPPLIER` INT(5) NOT NULL");
        DB::statement("ALTER TABLE `beli_detail` DROP COLUMN `ID_SUPPLIER`");
    }
}
