@extends('layouts.app')

@section('title','Detail Pembelian')
    
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Pembelian</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        Tambah detail pembelian
      </div>
      <div class="card-body">
        <div class="form-pembelian-submit mb-2">
            <div class="form-group">
                <label for="name">Tgl Pembelian</label>
                <input type="text" name="tgl_pembelian" id="tgl_pembelian" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-primary btn-sm btn-detail-pembelian my-1 ml-auto">Tambah</a>
        <div class="table-responsive">
            <table class="table detail-pembelian">
                <thead class="thead-dark">
                    <tr>
                      <th>Supplier</th>
                      <th>Nama Barang</th>
                      <th>Harga</th>
                      <th>Qty</th>
                      <th>Sub Total</th>
                    </tr>
                  </thead>
                  <tbody id="list-detail">
                      
                  </tbody>
            </table>
        </div>
        <a href="javascript:;" class="btn btn-success btn-sm btn-block btn-save">Simpan</a>
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

        $('#myModal').find('#qty_beli').mask("#.##0", {reverse: true});

        $('.btn-detail-pembelian').click(function(e){
        e.preventDefault();
        $('#action_button').html("Simpan");
        $('#myModal').modal('show');
        });

        get_detail();

        function get_detail(query = ''){
          $.ajax({
            url: "{{ route('get.detail.pembelian') }}",
            method: "GET",
            data:{query:query},
            dataType:'json',
            success:function(response){
                let html = '';
                let total = 0;
            $('.detail-pembelian #list-detail').empty();
              $.each(response.result, function(i,v){
                  html +=`
                    <tr>
                        <td>`+v.NAMA_SUPPLIER+`</td>
                        <td>`+v.NAMA_BARANG+`</td>
                        <td>`+v.HARGA+`</td>
                        <td>`+v.QTY+`</td>
                        <td>`+v.SUB_TOTAL+`</td>
                    </tr>
                  `;
                  total += v.SUB_TOTAL;
                });
                html += `
                    <tr>
                          <td colspan="4">Total</td>
                          <td id="total_beli">`+parseInt(total).formatMoney()+`</td>
                    </tr>
                `;
                $('.detail-pembelian #list-detail').append(html);
            }
          });
        }

        $('#myModal #supplier').on('change',function(){
            let id_supplier = $(this).val();
            var url = '{{ route("pembelian.change.supplier",":id") }}';
            $.ajax({
                url: url.replace(':id',id_supplier),
                dataType:'json',
                success:function(res){
                    let html = '';
                    if(res.data == ''){
                      swal({
                          title: "Maaf",
                          type:'info',
                          text:'Data Barang dari supplier tidak ada',
                          confirmButtonText: 'OK',
                      });
                    }else{
                        $('#barang').empty();
                        $('#barang').append('<option value="">Pilih Barang</option>');
                        $.each(res.data,function(i,data){
                        $('#barang').append(`<option value="`+data.ID_BARANG+`">`+data.NAMA_BARANG+`</option>`)
                        });
                    }
                }
            });
          });

          $('#myModal #barang').on('change',function(){
            let id_barang = $(this).val();
            var url = '{{ route("pembelian.change.barang",":id") }}';
            $.ajax({
                url: url.replace(':id',id_barang),
                dataType:'json',
                success:function(res){
                    let html = '';
                    $('#harga_beli').val('');
                    $('#harga_beli').val(parseInt(res.data.HARGA_BELI).formatMoney());
                }
            });
        });

        $(document).on("change keyup blur","#qty_beli",function(){
            let sub_total = 0;
            let qty = $(this).cleanVal();
            let harga_beli = getValueDecimal($('#harga_beli').val());

            sub_total = (qty*harga_beli).toFixed(0);
            $('#sub_total_beli').val(parseInt(sub_total).formatMoney());
        });

        $('#myModal .btn-simpan-detail').on('click',function(){
            let $btn = $(this);
            let formData = new FormData;
            let $page = $('#myModal');
            formData.append('supplier', $page.find('#supplier').val());
            formData.append('barang', $page.find('#barang').val());
            formData.append('harga_beli', $page.find('#harga_beli').val());
            formData.append('qty_beli', $page.find('#qty_beli').val());
            formData.append('sub_total_beli', $page.find('#sub_total_beli').val());
            $btn.addClass('disabled');
            axios.post('/pembelian/save-detail',formData)
            .then(response => {
                if(response.data.status === 'SUCCESS'){
                    swal({
                          title: "Berhasil",
                          type:'success',
                          text:response.data.message,
                          confirmButtonText: 'OK',
                      })
                    .then(function(){
                        $btn.closest('.modal-footer').find('.btn-cancel').trigger('click');
                        get_detail();
                    });        
                    }else{
                        Swal.fire({
                            "title":"Maaf",
                            "customClass":{"container":"swal-alert-error"},
                            "allowOutsideClick":false,
                            "html":"<div>"+response.data.message+"<\/div>"
                        });
                    }
            })
            .catch(function (error) {
            if(error.response && error.response.status==422){
            Swal.fire({
                "title":"Maaf",
                "customClass":{"container":"swal-alert-error"},
                "allowOutsideClick":false,
                "html":"<div>Input tidak benar<\/div>"
            });
            
            $('#myModal').find(".is-invalid").removeClass('is-invalid');
            if(error.response.data.errors){
                $.each(error.response.data.errors, function(key,val){
                $("#myModal").find('#'+key).addClass('is-invalid');
                $("#myModal").find('#'+key).parent().find('.invalid-feedback').html(val);
                });
            }
            }
            })
            .finally(function(){
                $btn.removeClass('disabled');
            });
        });

        $('.btn-save').on('click',function(){
            let $btn = $(this);
            let formData = new FormData;
            let $page = $('.detail-pembelian #list-detail');
            formData.append('total_beli', $('.detail-pembelian #list-detail').find('#total_beli').html());
            formData.append('tgl_pembelian', $('.card-body').find('#tgl_pembelian').val());
            // formData.append('keterangan_pembelian', $page.find('#keterangan_pembelian').val());
            $btn.addClass('disabled');
            axios.post('/pembelian/save',formData)
            .then(response => {
                if(response.data.status === 'SUCCESS'){
                    swal({
                          title: "Berhasil",
                          type:'success',
                          text:response.data.message,
                          confirmButtonText: 'OK',
                      })
                    .then(function(){
                        // $btn.closest('.modal-footer').find('.btn-cancel').trigger('click');
                        get_detail();
                    });        
                    }else{
                        swal({
                          title: "Maaf",
                          type:'info',
                          text:response.data.message,
                          confirmButtonText: 'OK',
                      })
                    }
            })
            .catch(function (error) {
            if(error.response && error.response.status==422){
            swal({
                title: "Maaf",
                type:'Info',
                text:'Input tidak benar',
                confirmButtonText: 'OK',
            })
            
            $('.form-pembelian-submit').find(".is-invalid").removeClass('is-invalid');
            if(error.response.data.errors){
                $.each(error.response.data.errors, function(key,val){
                $(".form-pembelian-submit").find('#'+key).addClass('is-invalid');
                $(".form-pembelian-submit").find('#'+key).parent().find('.invalid-feedback').html(val);
                });
            }
            }
            })
            .finally(function(){
                $btn.removeClass('disabled');
            });
        });

        //CLOSE FORM----------------------------------------
        $('#myModal').on('hidden.bs.modal',function(){
            $('#pembelian-submit')[0].reset();
            $('#barang').empty();
            $('#form_result').html('');
        });
      });
    </script>

    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form id="pembelian-submit">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tgl Pembelian</label>
                        <input type="text" name="tgl_pembelian" id="tgl_pembelian" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div>
                        <label for="name">Supplier</label>
                        <select name="supplier" id="supplier" class="form-control">
                            <option value="">--Pilih Supplier--</option>
                            @foreach ($suppliers as $sp)
                                <option value="{{ $sp->ID_SUPPLIER }}">{{ $sp->NAMA_SUPPLIER }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div>
                        <label for="name">Barang</label>
                        <select name="barang" id="barang" class="form-control">
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Harga</div>
                                </div>
                                <input type="text" name="harga_beli" id="harga_beli" class="form-control" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Qty</div>
                                </div>
                                <input type="text" name="qty_beli" id="qty_beli" class="form-control" value="0" onfocus="this.select();">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Total</label>
                        <input type="text" name="sub_total_beli" id="sub_total_beli" class="form-control" value="0" readonly>
                        <div class="invalid-feedback"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect btn-cancel" data-dismiss="modal">Kembali</button>
                <a href="javascript:;" class="btn btn-primary waves-effect waves-light btn-simpan-detail" id="action_button"></a>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endpush