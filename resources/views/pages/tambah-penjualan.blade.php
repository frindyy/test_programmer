@extends('layouts.app')

@section('title','Detail Penjualan')
    
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail penjualan</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        Tambah detail 
      </div>
      <div class="card-body">
        <div class="form-penjualan-submit mb-2">
            <div class="form-group">
                <label for="name">Tgl penjualan</label>
                <input type="text" name="tgl_penjualan" id="tgl_penjualan" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                <div class="invalid-feedback"></div>
            </div>
            <div>
                <label for="name">Customer</label>
                <select name="customer" id="customer" class="form-control">
                    <option value="">--Pilih Customer--</option>
                    @foreach ($customers as $cs)
                        <option value="{{ $cs->ID_CUSTOMER }}">{{ $cs->NAMA_CUSTOMER }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-primary btn-sm btn-detail-penjualan my-1 ml-auto">Tambah</a>
        <div class="table-responsive">
            <table class="table detail-penjualan">
                <thead class="thead-dark">
                    <tr>
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

        $('#myModal').find('#qty_jual').mask("#.##0", {reverse: true});

        $('.btn-detail-penjualan').click(function(e){
        e.preventDefault();
        $('#action_button').html("Simpan");
        $('#myModal').modal('show');
        });

        get_detail();

        function get_detail(query = ''){
          $.ajax({
            url: "{{ route('get.detail.penjualan') }}",
            method: "GET",
            data:{query:query},
            dataType:'json',
            success:function(response){
                let html = '';
                let total = 0;
            $('.detail-penjualan #list-detail').empty();
              $.each(response.result, function(i,v){
                  html +=`
                    <tr>
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
                          <td colspan="3">Total</td>
                          <td id="total_jual">`+parseInt(total).formatMoney()+`</td>
                    </tr>
                `;
                $('.detail-penjualan #list-detail').append(html);
            }
          });
        }

          $('#myModal #barang').on('change',function(){
            let id_barang = $(this).val();
            var url = '{{ route("penjualan.change.barang",":id") }}';
            $.ajax({
                url: url.replace(':id',id_barang),
                dataType:'json',
                success:function(res){
                    let html = '';
                    $('#harga_jual').val('');
                    $('#harga_jual').val(parseInt(res.data.HARGA_JUAL).formatMoney());
                }
            });
        });

        $(document).on("change keyup blur","#qty_jual",function(){
            let sub_total = 0;
            let qty = $(this).cleanVal();
            let harga_jual = getValueDecimal($('#harga_jual').val());

            sub_total = (qty*harga_jual).toFixed(0);
            $('#sub_total_jual').val(parseInt(sub_total).formatMoney());
        });

        $('#myModal .btn-simpan-detail').on('click',function(){
            let $btn = $(this);
            let formData = new FormData;
            let $page = $('#myModal');
            formData.append('barang', $page.find('#barang').val());
            formData.append('harga_jual', $page.find('#harga_jual').val());
            formData.append('qty_jual', $page.find('#qty_jual').val());
            formData.append('sub_total_jual', $page.find('#sub_total_jual').val());
            $btn.addClass('disabled');
            axios.post('/penjualan/save-detail',formData)
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
            let $page = $('.detail-penjualan #list-detail');
            formData.append('total_jual', $('.detail-penjualan #list-detail').find('#total_jual').html());
            formData.append('tgl_penjualan', $('.card-body').find('#tgl_penjualan').val());
            formData.append('customer', $('.card-body').find('#customer').val());
            formData.append('detail', '');
            // formData.append('keterangan_penjualan', $page.find('#keterangan_penjualan').val());
            $btn.addClass('disabled');
            axios.post('/penjualan/save',formData)
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
                        $('.card-body').find('#customer').val('');
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
            
            $('.form-penjualan-submit').find(".is-invalid").removeClass('is-invalid');
            if(error.response.data.errors){
                $.each(error.response.data.errors, function(key,val){
                $(".form-penjualan-submit").find('#'+key).addClass('is-invalid');
                $(".form-penjualan-submit").find('#'+key).parent().find('.invalid-feedback').html(val);
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
            $('#penjualan-submit')[0].reset();
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
                <h5 class="modal-title mt-0" id="myModalLabel">penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form id="penjualan-submit">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tgl penjualan</label>
                        <input type="text" name="tgl_penjualan" id="tgl_penjualan" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div>
                        <label for="name">Barang</label>
                        <select name="barang" id="barang" class="form-control">
                            <option value="">--Pilih Barang--</option>
                            @foreach ($barang as $br)
                                <option value="{{ $br->ID_BARANG }}">{{ $br->NAMA_BARANG.'-'.$br->STOCK_BARANG }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Harga</div>
                                </div>
                                <input type="text" name="qty_jual" id="harga_jual" class="form-control" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Qty</div>
                                </div>
                                <input type="text" name="qty_jual" id="qty_jual" class="form-control" value="0" onfocus="this.select();">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Total</label>
                        <input type="text" name="sub_total_jual" id="sub_total_jual" class="form-control" value="0" readonly>
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