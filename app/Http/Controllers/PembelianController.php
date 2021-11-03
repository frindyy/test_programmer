<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\Barang;
use App\BeliHeader;
use App\BeliDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $data_details = DB::Table('beli_header')->select('*')
        ->orderBy('TGL_INPUT_BELI','asc')
        ->get();
        // dd($data_details);
        return view('pages.pembelian',[
            'data_details' => $data_details,
        ]);
    }

    public function changeSupplier($id)
    {
        $data = DB::table('barang')->select('barang.*','supplier.NAMA_SUPPLIER')
        ->join('supplier','supplier.ID_SUPPLIER','=','barang.ID_SUPPLIER')->where('barang.ID_SUPPLIER',$id)->get();

        return response()->json(['data' => $data]);
    }

    public function changeBarang($id)
    {
        $data = DB::table('barang')->select('barang.*')->where('ID_BARANG',$id)->first();

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('pages.tambah-pembelian',[
            'suppliers' => $suppliers,
        ]);
    }

    public function getDetail()
    {
        $data = DB::table('beli_detail')->select('beli_detail.*','barang.NAMA_BARANG','barang.HARGA_BELI','supplier.NAMA_SUPPLIER')
        ->join('barang','barang.ID_BARANG','=','beli_detail.ID_BARANG')
        ->join('supplier','supplier.ID_SUPPLIER','=','beli_detail.ID_SUPPLIER')
        ->where('STATUS_DETAIL_BELI','0')
        ->get();
        return response()->json(['status'=>'SUCCESS','result' => $data]);
    }

    public function saveDetail(Request $request)
    {
        $supplier=intval($request->input('supplier'));
        $barang=intval($request->input('barang'));
        $qty_beli=intval(str_replace(".", "", $request->input('qty_beli')));
        $harga_beli=intval(str_replace(".", "", $request->input('harga_beli')));
        $sub_total_beli=intval(str_replace(".", "", $request->input('sub_total_beli')));

        $validations = [
            'supplier' => 'required',
            'barang' => 'required',
            'qty_beli' => ['required','gt:0'],
            'harga_beli' => ['required','gt:0'],
            'sub_total_beli' => ['required','gt:0'],
        ];
        $this->validateWith($validations, $request);

        $pesan_sukses = 'Data berhasil di tambahkan';

        $detail_beli = new BeliDetail;
        $detail_beli->ID_BELI_HEADER = 0;
        $detail_beli->ID_SUPPLIER = $supplier;
        $detail_beli->ID_BARANG = $barang;
        $detail_beli->HARGA = $harga_beli;
        $detail_beli->QTY = $qty_beli;
        $detail_beli->SUB_TOTAL = $sub_total_beli;
        $detail_beli->save();

        return response()->json(['status'=>'SUCCESS','message'=>$pesan_sukses]);
    }

    public function save(Request $request)
    {
        $tgl_pembelian=date('Y-m-d',strtotime($request->input('tgl_pembelian')));
        $total_beli=intval(str_replace(".", "", $request->input('total_beli')));

        $validations = [
            'tgl_pembelian' => 'required',
        ];
        $this->validateWith($validations, $request);

        $pesan_sukses = 'Data pembelian berhasil di Simoan';

        $header_beli = new BeliHeader;
        $header_beli->TGL_INPUT_BELI = $tgl_pembelian;
        $header_beli->TOTAL_BELI = $total_beli;
        $header_beli->STATUS_HEADER_BELI = '1';
        $header_beli->save();

        DB::table('beli_detail')
            ->where('STATUS_DETAIL_BELI','0')
            ->update([
                'ID_BELI_HEADER' => $header_beli->ID_BELI_HEADER,
                'STATUS_DETAIL_BELI' => '1',
        ]);

        //tambah stok
        $data_detail = BeliDetail::where('ID_BELI_HEADER',$header_beli->ID_BELI_HEADER)->get();
        foreach ($data_detail as $item) {
            $data_barang = Barang::where('ID_BARANG', '=', $item->ID_BARANG)->increment('STOCK_BARANG', $item->QTY);
        }

        return response()->json(['status'=>'SUCCESS','message'=>$pesan_sukses]);
    }
}
