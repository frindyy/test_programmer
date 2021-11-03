<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index')->name('dashboard.index');
Route::get('/customer', 'CustomerController@index')->name('customer.index');
Route::get('/barang', 'BarangController@index')->name('barang.index');
Route::get('/supplier', 'SupplierController@index')->name('supplier.index');

Route::get('/pembelian', 'PembelianController@index')->name('pembelian.index');
Route::get('/pembelian/tambah', 'PembelianController@create')->name('pembelian.create');
Route::get('/pembelian/get-detail-pembelian', 'PembelianController@getDetail')->name('get.detail.pembelian');
Route::get('/pembelian/change-supplier/{id}', 'PembelianController@changeSupplier')->name('pembelian.change.supplier');
Route::get('/pembelian/change-barang/{id}', 'PembelianController@changeBarang')->name('pembelian.change.barang');
Route::post('/pembelian/save-detail', 'PembelianController@saveDetail')->name('pembelian.save.detail');
Route::post('/pembelian/save', 'PembelianController@save')->name('pembelian.save');

Route::get('/penjualan', 'PenjualanController@index')->name('penjualan.index');
Route::get('/penjualan/tambah', 'PenjualanController@create')->name('penjualan.create');
Route::get('/penjualan/get-detail-penjualan', 'PenjualanController@getDetail')->name('get.detail.penjualan');
Route::get('/penjualan/change-barang/{id}', 'PenjualanController@changeBarang')->name('penjualan.change.barang');
Route::post('/penjualan/save-detail', 'PenjualanController@saveDetail')->name('penjualan.save.detail');
Route::post('/penjualan/save', 'PenjualanController@save')->name('penjualan.save');
