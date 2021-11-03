<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `beli_detail` ADD COLUMN `STATUS_DETAIL_BELI` enum('0','1','2') NOT NULL DEFAULT '0'");
        DB::statement("ALTER TABLE `beli_header` ADD COLUMN `STATUS_HEADER_BELI` enum('0','1','2') NOT NULL DEFAULT '0'");
        DB::statement("ALTER TABLE `jual_detail` ADD COLUMN `STATUS_DETAIL_JUAL` enum('0','1','2') NOT NULL DEFAULT '0'");
        DB::statement("ALTER TABLE `jual_header` ADD COLUMN `STATUS_HEADER_JUAL` enum('0','1','2') NOT NULL DEFAULT '0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `beli_detail` DROP COLUMN `STATUS_DETAIL_BELI`");
        DB::statement("ALTER TABLE `beli_header` DROP COLUMN `STATUS_HEADER_BELI`");
        DB::statement("ALTER TABLE `jual_detail` DROP COLUMN `STATUS_DETAIL_JUAL`");
        DB::statement("ALTER TABLE `jual_header` DROP COLUMN `STATUS_HEADER_JUAL`");
    }
}
