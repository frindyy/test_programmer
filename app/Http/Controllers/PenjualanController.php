<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Customer;
use App\JualDetail;
use App\JualHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $data_details = DB::Table('jual_header')->select('jual_header.*','customer.NAMA_CUSTOMER')
        ->join('customer','customer.ID_CUSTOMER','=','jual_header.ID_CUSTOMER')
        ->orderBy('customer.NAMA_CUSTOMER','asc')
        ->get();
        // dd($data_details);
        return view('pages.penjualan',[
            'data_details' => $data_details,
        ]);
    }

    public function changeBarang($id)
    {
        $data = DB::table('barang')->select('barang.*')->where('ID_BARANG',$id)->first();

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $customers = Customer::all();
        $barang = Barang::all();
        return view('pages.tambah-penjualan',[
            'customers' => $customers,
            'barang' => $barang,
        ]);
    }

    public function getDetail()
    {
        $data = DB::table('jual_detail')->select('jual_detail.*','barang.NAMA_BARANG','barang.HARGA_BELI')
        ->join('barang','barang.ID_BARANG','=','jual_detail.ID_BARANG')
        ->where('STATUS_DETAIL_JUAL','0')
        ->get();
        return response()->json(['status'=>'SUCCESS','result' => $data]);
    }

    public function saveDetail(Request $request)
    {
        $barang=intval($request->input('barang'));
        $qty_jual=intval(str_replace(".", "", $request->input('qty_jual')));
        $harga_jual=intval(str_replace(".", "", $request->input('harga_jual')));
        $sub_total_jual=intval(str_replace(".", "", $request->input('sub_total_jual')));

        $validations = [
            'barang' => 'required',
            'qty_jual' => ['required','gt:0'],
            'harga_jual' => ['required','gt:0'],
            'sub_total_jual' => ['required','gt:0'],
        ];
        $this->validateWith($validations, $request);

        $pesan_sukses = 'Data berhasil di tambahkan';

        $detail_jual = new JualDetail;
        $detail_jual->ID_JUAL_HEADER = 0;
        $detail_jual->ID_BARANG = $barang;
        $detail_jual->HARGA = $harga_jual;
        $detail_jual->QTY = $qty_jual;
        $detail_jual->SUB_TOTAL = $sub_total_jual;
        $detail_jual->save();

        return response()->json(['status'=>'SUCCESS','message'=>$pesan_sukses]);
    }

    public function save(Request $request)
    {
        $customer=$request->input('customer');
        $tgl_penjualan=date('Y-m-d',strtotime($request->input('tgl_penjualan')));
        $total_jual=intval(str_replace(".", "", $request->input('total_jual')));

        $validations = [
            'tgl_penjualan' => 'required',
            'customer' => 'required',
        ];
        $this->validateWith($validations, $request);

        $pesan_sukses = 'Data pemjualan berhasil di Simoan';

        $header_jual = new JualHeader;
        $header_jual->ID_CUSTOMER = $customer;
        $header_jual->TGL_INPUT_JUAL = $tgl_penjualan;
        $header_jual->TOTAL_JUAL = $total_jual;
        $header_jual->STATUS_HEADER_JUAL = '1';
        $header_jual->save();

        DB::table('jual_detail')
            ->where('STATUS_DETAIL_JUAL','0')
            ->update([
                'ID_JUAL_HEADER' => $header_jual->ID_JUAL_HEADER,
                'STATUS_DETAIL_JUAL' => '1',
        ]);

        //tambah stok
        $data_detail = JualDetail::where('ID_JUAL_HEADER',$header_jual->ID_JUAL_HEADER)->get();
        foreach ($data_detail as $item) {
            $data_barang = Barang::where('ID_BARANG', '=', $item->ID_BARANG)->decrement('STOCK_BARANG', $item->QTY);
        }

        return response()->json(['status'=>'SUCCESS','message'=>$pesan_sukses]);
    }
}
