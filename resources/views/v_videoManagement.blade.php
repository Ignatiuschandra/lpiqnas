@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Video Management')
@section('judul_halaman', 'Video Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Video</a>
            </div>
          </div>

          <table class="table" id="tabel-video">
            <thead>
              <tr>
                <th>Judul Video</th>
                <th>Link</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card-->
   </div>
<!-- /.col -->
</div>
<!-- /.row -->


  <!-- modal hapus -->
  <div class="modal fade" id="modal-hapus">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hapus Video</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Video ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <input type="hidden" id="video_id" name="video_id">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataVideo" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- modal edit -->
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data Video</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formEdit">
            <input type="hidden" name="id" id="editId">
            <div class="card-body">
              <div class="form-group">
                <label for="JudulVideo">Judul Video</label>
                <input type="text" class="form-control" name="judul" id="editJudulVideo" placeholder="">
              </div>
              <div class="form-group">
                <label for="Link">Link</label>
                <input type="text" class="form-control" name="link" id="editLink" placeholder="">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataVideo" type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- modal tambah -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Video</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="JudulVideo">Judul Video</label>
                <input type="text" class="form-control" name="judul" id="addJudul" placeholder="Masukkan Judul Video">
              </div>
              <div class="form-group">
                <label for="Link">Link</label>
                <input type="text" class="form-control" name="link" id="addLink" placeholder="Masukkan Link Video">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataVideo" type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</section>
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

  $(document).ready( function () {
    tableVideo = $('#tabel-video').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('video-management/get-json-video') }}",
      columns: [
          { data: 'vb_judul' },
          { data: null,
            render: function(data, type, row){
              if (data.vb_link != null) {
                return `<a target="_blank" href="`+data.vb_link+`">`+data.vb_link+`</a>`;
              }else{
                return '-';
              }
          }},
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataVideo(`+data.vb_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.vb_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 2,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataVideo').on('click', function(){
    $.ajax({
      url:"{{ url('video-management/tambah-video') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Video.'
          });
          tableVideo.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Video. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Video. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#video_id').val(id);
  }

  $('#hapusDataVideo').on('click', function(){
    $.ajax({
      url:"{{ url('video-management/hapus-video') }}",
      method:"POST", 
      data:{video_id : $('#video_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Video.'
          });
          tableVideo.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Video. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Video. Hubungi Developer!'
        });
      }
    });
  });

  function getDataVideo(id){
    $.ajax({
      url:"{{ url('video-management/get-video') }}",
      method:"POST", 
      data:{video_id : id},
      success:function(response) {
        $('#editJudulVideo').val(response.data.vb_judul);
        $('#editLink').val(response.data.vb_link);
        $('#editId').val(response.data.vb_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Video. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataVideo').on('click', function(){
    $.ajax({
      url:"{{ url('video-management/update-video') }}",
      method:"POST", 
      data:$('#formEdit').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Video.'
          });
          tableVideo.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Video. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Video. Hubungi Developer!'
        });
      }
    });
  });
</script>
@endsection