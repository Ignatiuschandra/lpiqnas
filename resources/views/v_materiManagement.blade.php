@extends('layouts.master')
@section('title_nav', 'LPIQNAS | User Management')
@section('judul_halaman', 'User Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Materi</a>
            </div>
          </div>

          <table class="table" id="tabel-materi">
            <thead>
              <tr>
                <th>Nama Materi</th>
                <th>Tingkat / Kelas</th>
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
          <h4 class="modal-title">Hapus Materi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Materi ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Edit Data Materi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="exampleInputFile">
                    <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                  </div>
                  <div class="input-group-append">
                    <span class="input-group-text" id="">Upload</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
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
          <h4 class="modal-title">Tambah Data Materi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="addNama">Nama Materi</label>
                <input type="text" class="form-control" id="addNama" placeholder="Masukkan Nama / Judul Materi">
              </div>
              <div class="form-group">
                <label for="addNama">Tingkat / Kelas Materi</label>
                <select>Materi</select>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
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
  var tableMateri;

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
    tableMateri = $('#tabel-materi').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('materi-management/get-json-materi') }}",
      columns: [
          { data: 'materi_nama' },
          { data: 'materi_tingkat' },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataMateri(`+data.materi_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.materi_id+`)"><i class="fas fa-trash"></i></a>
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

  $('#tambahDataMateri').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/tambah-materi') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data materi.'
          });
          tableMateri.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data materi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data materi. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#materi_id').val(id);
  }

  $('#hapusDataMateri').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/hapus-materi') }}",
      method:"POST", 
      data:{materi_id : $('#materi_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data materi.'
          });
          tableMateri.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data materi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data materi. Hubungi Developer!'
        });
      }
    });
  });

  function getDataMateri(id){
    $.ajax({
      url:"{{ url('user-management/get-materi') }}",
      method:"POST", 
      data:{materi_id : id},
      success:function(response) {
        $('#editNamaLengkap').val(response.data.materi_nama_lengkap);
        $('#editUsername').val(response.data.materi_username);
        $('#editAlamat').val(response.data.materi_alamat);
        $('#editTgl').val(response.data.materi_dob);
        $('#editTelepon').val(response.data.materi_telepon);
        $('#editId').val(response.data.materi_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data materi. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataMateri').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/update-materi') }}",
      method:"POST", 
      data:$('#formEdit').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data materi.'
          });
          tableMateri.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data materi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data materi. Hubungi Developer!'
        });
      }
    });
  });
</script>
@endsection