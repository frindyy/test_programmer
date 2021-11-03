<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement("CREATE TABLE `customer` (
            `ID_CUSTOMER` int(5) NOT NULL AUTO_INCREMENT,
            `NAMA_CUSTOMER` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `EMAIL_CUSTOMER` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `ALAMAT_CUSTOMER` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`ID_CUSTOMER`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `barang` (
            `ID_BARANG` int(5) NOT NULL AUTO_INCREMENT,
            `ID_SUPPLIER` int(5) NOT NULL,
            `NAMA_BARANG` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `TGL_INPUT_BARANG` date NOT NULL,
            `STOCK_BARANG` int(5) NOT NULL,
            `HARGA_JUAL` int(20) NOT NULL,
            `HARGA_BELI` int(20) NOT NULL,
            PRIMARY KEY (`ID_BARANG`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `supplier` (
            `ID_SUPPLIER` int(5) NOT NULL AUTO_INCREMENT,
            `NAMA_SUPPLIER` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `TGL_INPUT_SUPPLIER` date NOT NULL,
            `ALAMAT_SUPPLIER` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `TELP_SUPPLIER` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`ID_SUPPLIER`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `jual_header` (
            `ID_JUAL_HEADER` int(5) NOT NULL AUTO_INCREMENT,
            `ID_CUSTOMER` int(5) NOT NULL,
            `TGL_INPUT_JUAL` date NOT NULL,
            `TOTAL_JUAL` int(20) NOT NULL,
            PRIMARY KEY (`ID_JUAL_HEADER`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `jual_detail` (
            `ID_JUAL_DETAIL` int(5) NOT NULL AUTO_INCREMENT,
            `ID_JUAL_HEADER` int(5) NOT NULL,
            `ID_BARANG` int(5) NOT NULL,
            `HARGA` int(20) NOT NULL,
            `QTY` int(10) NOT NULL,
            `SUB_TOTAL` int(20) NOT NULL,
            PRIMARY KEY (`ID_JUAL_DETAIL`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `beli_header` (
            `ID_BELI_HEADER` int(5) NOT NULL AUTO_INCREMENT,
            `ID_SUPPLIER` int(5) NOT NULL,
            `TGL_INPUT_BELI` date NOT NULL,
            `TOTAL_BELI` int(20) NOT NULL,
            PRIMARY KEY (`ID_BELI_HEADER`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::Statement("CREATE TABLE `beli_detail` (
            `ID_BELI_DETAIL` int(5) NOT NULL AUTO_INCREMENT,
            `ID_BELI_HEADER` int(5) NOT NULL,
            `ID_BARANG` int(5) NOT NULL,
            `HARGA` int(20) NOT NULL,
            `QTY` int(10) NOT NULL,
            `SUB_TOTAL` int(20) NOT NULL,
            PRIMARY KEY (`ID_beli_DETAIL`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('jual_header');
        Schema::dropIfExists('jual_detail');
        Schema::dropIfExists('beli_header');
        Schema::dropIfExists('beli_detail');
    }
}
