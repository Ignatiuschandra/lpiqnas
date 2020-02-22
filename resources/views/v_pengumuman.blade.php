@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Informasi dan Event')
@section('judul_halaman', 'Informasi dan Event')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Event</a>
            </div>
          </div>

          <table class="table" id="tabel-pengumuman">
            <thead>
              <tr>
                <th class="text-left">Judul Event</th>
                <th>Konten Event</th>
                <th>Pembuat Event</th>
                <th>Expired Date</th>
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
          <h4 class="modal-title">Hapus Event</h4>
          <input type="hidden" name="pengumuman_id" id="pengumuman_id">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Event ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataPengumuman" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Edit Event</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formEdit">
            <input type="hidden" name="id" id="editId">
            <div class="card-body">
              <label for="exampleInputFile">Foto Event</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="editExampleInputFile" name="file">
                  <label class="custom-file-label" for="exampleInputFile">Pilih Foto</label>
                </div>
              </div>
              <div class="form-group">
                <label for="Judul">Judul Event</label>
                <input name="judul" type="text" class="form-control" id="editJudul" placeholder="Masukkan Judul Event">
              </div>
              <div class="form-group">
                <label for="desc">Konten</label>
               <textarea name="konten" id="editKonten" class="form-control" rows="2" placeholder="Masukkan Konten Event"></textarea>
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat Event</label>
                <select name="pembuat" type="text" class="form-control" id="editPembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Tanggal">Tanggal Expired</label>
                <input name="tanggal" type="date" class="form-control" id="editTanggal" placeholder="Masukkan Tanggal Event">
              </div>       
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataPengumuman" type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
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
          <h4 class="modal-title">Tambah Event Kelas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <label for="exampleInputFile">Foto Event</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="addExampleInputFile" name="file">
                  <label class="custom-file-label" for="exampleInputFile">Pilih Foto</label>
                </div>
              </div>
              <div class="form-group">
                <label for="Judul">Judul Event</label>
                <input name="judul" type="text" class="form-control" id="addJudul" placeholder="Masukkan Judul Event">
              </div>
              <div class="form-group">
                <label for="desc">Konten</label>
               <textarea name="konten" id="addKonten" class="form-control" rows="2" placeholder="Masukkan Konten Event"></textarea>
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat Event</label>
                <select name="pembuat" type="text" class="form-control" id="addPembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Tanggal">Tanggal Expired</label>
                <input name="tanggal" type="date" class="form-control" id="addTanggal" placeholder="Masukkan Tanggal Event">
              </div>              
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataPengumuman" type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
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
  var tablePengumuman;

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
    tablePengumuman = $('#tabel-pengumuman').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('pengumuman/get-json-pengumuman') }}",
      columns: [
          { data: 'pengumuman_judul' },
          { data: 'pengumuman_konten' },
          { data: 'admin_nama_lengkap' },
          { data: 'pengumuman_expired' },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataPengumuman(`+data.pengumuman_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.pengumuman_id+`)"><i class="fas fa-trash"></i></a>
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

  $('#tambahDataPengumuman').on('click', function(){
    var formData = new FormData();
    formData.append('judul', $('#addJudul').val());
    formData.append('konten', $('#addKonten').val());
    formData.append('pembuat', $('#addPembuat').val());
    formData.append('tanggal', $('#addTanggal').val());
    formData.append('file', $('#addExampleInputFile')[0].files[0]);
    $.ajax({
      url:"{{ url('pengumuman/tambah-pengumuman') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Pengumuman.'
          });
          tablePengumuman.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Pengumuman. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Pengumuman. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#pengumuman_id').val(id);
  }

  $('#hapusDataPengumuman').on('click', function(){
    $.ajax({
      url:"{{ url('pengumuman/hapus-pengumuman') }}",
      method:"POST", 
      data:{pengumuman_id : $('#pengumuman_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Pengumuman.'
          });
          tablePengumuman.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Pengumuman. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data pengumuman. Hubungi Developer!'
        });
      }
    });
  });

  function getDataPengumuman(id){
    $.ajax({
      url:"{{ url('pengumuman/get-pengumuman') }}",
      method:"POST", 
      data:{pengumuman_id : id},
      success:function(response) {
        $('#editJudul').val(response.data.pengumuman_judul);
        $('#editKonten').val(response.data.pengumuman_konten);
        $('#editPembuat').val(response.data.pengumuman_pembuat_id);
        $('#editTanggal').val(response.data.pengumuman_expired);
        $('#editId').val(response.data.pengumuman_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Pengumuman. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataPengumuman').on('click', function(){
    var formData = new FormData();
    formData.append('judul', $('#editJudul').val());
    formData.append('konten', $('#editKonten').val());
    formData.append('pembuat', $('#editPembuat').val());
    formData.append('tanggal', $('#editTanggal').val());
    formData.append('file', $('#editExampleInputFile')[0].files[0]);
    $.ajax({
      url:"{{ url('pengumuman/update-pengumuman') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Pengumuman.'
          });
          tablePengumuman.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Pengumuman. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Pengumuman. Hubungi Developer!'
        });
      }
    });
  });
</script>
@endsection