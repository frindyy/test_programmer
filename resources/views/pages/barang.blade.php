@extends('layouts.app')

@section('title','Master Barang')
    
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Master Barang</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      {{-- <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
      </div> --}}
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="barangTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
              </tr>
            </thead>
            <tbody>
                @php
                    $no=1;
                @endphp
              @foreach ($barangs as $br)
                  <td>{{ $no++ }}</td>
                  <td>{{ $br->NAMA_BARANG }}</td>
                  <td>{{ $br->NAMA_SUPPLIER }}</td>
                  <td>{{ $br->STOCK_BARANG }}</td>
                  <td>{{ $br->HARGA_JUAL }}</td>
                  <td>{{ $br->HARGA_BELI }}</td>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

@endsection

@push('after-script')
    <script>
        $(document).ready(function() {
            $('#barangTable').DataTable();
        });
    </script>
@endpush