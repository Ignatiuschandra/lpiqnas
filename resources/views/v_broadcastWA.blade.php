@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Brodacast Whatsapp')
@section('judul_halaman', 'Brodacast Whatsapp')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">

    <div class="col-12">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" class="form-control" rows="4" placeholder="Masukkan Pesan Broadcast"></textarea>
          </div>
          <div class="form-group">
            <button id="sendWA" type="button" class="btn btn-success" style="float: right;" >Kirim</button>
          </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
<!-- /.col -->
</div>
<!-- /.row -->

<!-- modal pesan-->
  <div class="modal fade" id="modal-pesan">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Broadcast</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Pesan broadcast berhasil terkirim!</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

</section>
    <!-- /.content -->
  <!-- /.container-fluid -->
@endsection

@section('scriptTambahan')
<!-- page script -->
<script>
  var tableVideo;

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('#sendWA').on('click', function(){
    $(this).attr('disabled', true);
    $.ajax({
      url:"{{ url('broadcast-wa/kirim') }}",
      method:"POST", 
      data:{pesan: $('#pesan').val()},
      success:function(response) {
        if (response.success) {
          Toast.fire({
            type: 'success',
            title: 'Pesan berhasil dikirim!'
          });
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Video. Hubungi Developer!'
          });
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Video. Hubungi Developer!'
        });
      },
      complete:function(){
        $("#sendWA").removeAttr('disabled');
      }
    });
  });
</script>
@endsection