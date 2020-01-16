@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Jadwal Kelas')
@section('judul_halaman', 'Jadwal Kelas')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Jadwal</a>
            </div>
          </div>

          <table class="table text-center" id="tabel-jadwal">
            <thead>
              <tr>
                <th class="text-left">Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
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
          <h4 class="modal-title">Hapus Jadwal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="jadwal_id" id="jadwal_id">
          <p>Apakah Anda Yakin Ingin Menghapus Jadwal ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataJadwal" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Edit Jadwal</h4>
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
                <label for="MataPelajaran">Mata Pelajaran</label>
                <select class="form-control" name="materi" id="editMateri">
                  @foreach($materi as $m)
                  <option value="{{ $m['materi_id'] }}">
                    {{ $m['materi_nama'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <select class="form-control" name="kelas" id="editKelas">
                  @foreach($kelas as $k)
                  <option value="{{ $k['kelas_id'] }}">
                    {{ $k['kelas_tingkat'].' '.$k['kelas_nama'].' - '.$k['kelas_tahun_ajaran'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="Hari">Hari</label>
                <select type="text" class="form-control" name="hari" id="editHari" placeholder="Masukkan Jam Mulai">
                  <option value="" disabled selected>-- Pilih Hari</option>
                  <option value="1">Senin</option>
                  <option value="2">Selasa</option>
                  <option value="3">Rabu</option>
                  <option value="4">Kamis</option>
                  <option value="5">Jum'at</option>
                  <option value="6">Sabtu</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Mulai">Jam Mulai</label>
                <input type="text" class="form-control" name="mulai" id="editMulai" placeholder="Masukkan Jam Mulai">
              </div>
              <div class="form-group">
                <label for="Selesai">Jam Selesai</label>
                <input type="text" class="form-control" name="selesai" id="editSelesai" placeholder="Masukkan Jam Selesai">
              </div>      
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataJadwal" type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
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
          <h4 class="modal-title">Tambah Jadwal Kelas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="MataPelajaran">Mata Pelajaran</label>
                <select class="form-control" name="materi" id="addMateri">
                  @foreach($materi as $m)
                  <option value="{{ $m['materi_id'] }}">
                    {{ $m['materi_nama'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <select class="form-control" name="kelas" id="addKelas">
                  @foreach($kelas as $k)
                  <option value="{{ $k['kelas_id'] }}">
                    {{ $k['kelas_tingkat'].' '.$k['kelas_nama'].' - '.$k['kelas_tahun_ajaran'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="Hari">Hari</label>
                <select type="text" class="form-control" name="hari" id="addHari" placeholder="Masukkan Jam Mulai">
                  <option value="" disabled selected>-- Pilih Hari</option>
                  <option value="1">Senin</option>
                  <option value="2">Selasa</option>
                  <option value="3">Rabu</option>
                  <option value="4">Kamis</option>
                  <option value="5">Jum'at</option>
                  <option value="6">Sabtu</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Mulai">Jam Mulai</label>
                <input type="text" class="form-control" name="mulai" id="addMulai" placeholder="Masukkan Jam Mulai">
              </div>
              <div class="form-group">
                <label for="Selesai">Jam Selesai</label>
                <input type="text" class="form-control" name="selesai" id="addSelesai" placeholder="Masukkan Jam Selesai">
              </div>              
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataJadwal" type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
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

  $('#addMulai').bootstrapMaterialDatePicker({
    date: false,
    format: 'HH:mm'
  });

  $('#addSelesai').bootstrapMaterialDatePicker({
    date: false,
    format: 'HH:mm'
  });

  $('#editMulai').bootstrapMaterialDatePicker({
    date: false,
    format: 'HH:mm'
  });

  $('#editSelesai').bootstrapMaterialDatePicker({
    date: false,
    format: 'HH:mm'
  });

  $(document).ready( function () {
    tableVideo = $('#tabel-jadwal').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('jadwal/get-json-jadwal') }}",
      columns: [
          { data: 'materi_nama' },
          { data: null,
            render: function(data, type, row){
              return data.kelas_tingkat+' '+data.kelas_nama+' - '+data.kelas_tahun_ajaran
            },
            orderable:false 
          },
          { data: null,
            render: function(data, type, row){
              if (data.kj_hari == 1) {
                return 'Senin';
              }else if (data.kj_hari == 2) {
                return 'Selasa';
              }else if (data.kj_hari == 3) {
                return 'Rabu';
              }else if (data.kj_hari == 4) {
                return 'Kamis';
              }else if (data.kj_hari == 5) {
                return "Jum'at";
              }else if (data.kj_hari == 6) {
                return 'Sabtu';
              }
            },
            orderable:false 
          },
          { data: 'kj_mulai'},
          { data: 'kj_selesai'},
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataJadwal(`+data.kj_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.kj_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 0,
        className: 'text-left'
      },{
        targets: 5,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataJadwal').on('click', function(){
    $.ajax({
      url:"{{ url('jadwal/tambah-jadwal') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Jadwal.'
          });
          tableVideo.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Jadwal. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Jadwal. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#jadwal_id').val(id);
  }

  $('#hapusDataJadwal').on('click', function(){
    $.ajax({
      url:"{{ url('jadwal/hapus-jadwal') }}",
      method:"POST", 
      data:{jadwal_id : $('#jadwal_id').val()},
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

  function getDataJadwal(id){
    $.ajax({
      url:"{{ url('jadwal/get-jadwal') }}",
      method:"POST", 
      data:{jadwal_id : id},
      success:function(response) {
        $('#editId').val(response.data.kj_id);
        $('#editMateri').val(response.data.kj_materi_id);
        $('#editKelas').val(response.data.kj_kelas_id);
        $('#editHari').val(response.data.kj_hari);
        $('#editMulai').val(response.data.kj_mulai);
        $('#editSelesai').val(response.data.kj_selesai);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Jadwal. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataJadwal').on('click', function(){
    $.ajax({
      url:"{{ url('jadwal/update-jadwal') }}",
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