@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Materi Management')
@section('judul_halaman', 'Materi Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah" ><i class="fas fa-plus-square mr-2"></i> Tambah Materi</a>
            </div>
          </div>

          <table class="table" id="tabel-materi" width="100%">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Kelas</th>
                <th>Deskripsi</th>
                <th>Link File</th>
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
          <input type="hidden" id="materi_id">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataMateri" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <form role="form" id="formEdit">
            <input type="hidden" name="id" id="editId">
            <div class="card-body">
                  <div class="form-group">
                    <label for="judul">Judul Materi</label>
                    <input type="text" class="form-control" id="editJudul" placeholder="Masukkan Judul Materi" name="judul">
                  </div>

                  <div class="form-group">
                    <label>Pilih Kelas</label>
                    <select class="form-control" name="kelas" id="editKelas">
                      <option value="10">Kelas 10</option>
                      <option value="11">Kelas 11</option>
                      <option value="12">Kelas 12</option>
                    </select>
                  </div>

                  <div class="form-group">
                  <label for="desc">Deskripsi Materi</label>
                  <textarea name="desc" id="editDesc" class="form-control" rows="4" placeholder="Masukkan Deskripsi"></textarea>
                  </div>

                  <label for="exampleInputFile">Upload File</label>
                  <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="editExampleInputFile" name="file">
                    <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                  </div>
                  <div class="input-group-append mb-3">
                    <span class="input-group-text" id="">Upload</span>
                  </div>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataMateri" type="button" class="btn btn-success toastrEditSuccess">Edit</button>
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
          <form role="form" enctype="multipart/form-data" id="formTambah">
            <div class="card-body">
                  <div class="form-group">
                    <label for="judul">Judul Materi</label>
                    <input type="text" class="form-control" id="addJudul" placeholder="Masukkan Judul Materi" name="judul">
                  </div>

                  <div class="form-group">
                    <label>Pilih Kelas</label>
                    <select class="form-control" name="kelas" id="addKelas">
                      <option value="10">Kelas 10</option>
                      <option value="11">Kelas 11</option>
                      <option value="12">Kelas 12</option>
                    </select>
                  </div>

                  <div class="form-group">
                  <label for="desc">Deskripsi Materi</label>
                  <textarea name="desc" id="addDesc" class="form-control" rows="4" placeholder="Masukkan Deskripsi"></textarea>
                  </div>

                  <label for="exampleInputFile">Upload File</label>
                  <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="addExampleInputFile" name="file">
                    <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                  </div>
                  <div class="input-group-append mb-3">
                    <span class="input-group-text" id="">Upload</span>
                  </div>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataMateri" type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
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
          { data: 'materi_detail' },
          { data: null,
            orderable: false,
            render: function(data, type, row){
              return `<a target="_blank" href="{{ url('materi-management/download/`+data.materi_id+`') }}">Download</a>`;
            }
          },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataMateri(`+data.materi_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.materi_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 4,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataMateri').on('click', function(){
    var formData = new FormData();
    formData.append('judul', $('#addJudul').val());
    formData.append('kelas', $('#addKelas').val());
    formData.append('desc', $('#addDesc').val());
    formData.append('file', $('#addExampleInputFile')[0].files[0]);
    $.ajax({
      url:"{{ url('materi-management/tambah-materi') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
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
      url:"{{ url('materi-management/hapus-materi') }}",
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
      url:"{{ url('materi-management/get-materi') }}",
      method:"POST", 
      data:{materi_id : id},
      success:function(response) {
        $('#editJudul').val(response.data.materi_nama);
        $('#editKelas').val(response.data.materi_tingkat);
        $('#editDesc').val(response.data.materi_detail);
        $('#editId').val(id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data materi. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataMateri').on('click', function(){
    var formData = new FormData();
    formData.append('id', $('#editId').val());
    formData.append('judul', $('#editJudul').val());
    formData.append('kelas', $('#editKelas').val());
    formData.append('desc', $('#editDesc').val());
    formData.append('file', $('#editExampleInputFile')[0].files[0]);
    $.ajax({
      url:"{{ url('materi-management/update-materi') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
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