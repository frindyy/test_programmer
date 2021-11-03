<?php

namespace App\Http\Controllers;
use App\Barang;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = DB::table('barang')->select('barang.*','supplier.NAMA_SUPPLIER')
        ->join('supplier','supplier.ID_SUPPLIER','=','barang.ID_SUPPLIER')->get();
        return view('pages.barang',[
            'barangs' => $barangs
        ]);
    }
}
