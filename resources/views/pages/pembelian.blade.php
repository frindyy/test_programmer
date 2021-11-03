@extends('layouts.app')

@section('title','Pembelian')
    
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pembelian</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary btn-sm btn-add-pembelian">Tambah</a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="pembelianTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Tgl</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_details as $x => $item)
                  <tr>
                    <td>{{ ($x+1) }}</td>
                    <td>{{ date('d-m-Y',strtotime($item->TGL_INPUT_BELI)) }}</td>
                    <td>{{ number_format($item->TOTAL_BELI,0,',','.') }}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection

@push('after-script')
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <script>
    $(document).ready(function() {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

          $('#pembelianTable').DataTable();

          //CLOSE FORM----------------------------------------
        // $('#myModal').on('hidden.bs.modal',function(){
        //     $('#pembelian-submit')[0].reset();
        //     $('#myModal #details').empty();
        //     $('#form_result').html('');
        // });
      });
    </script>
@endpush