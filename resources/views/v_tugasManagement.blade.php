@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Tugas Management')
@section('judul_halaman', 'Tugas Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Tugas</a>
            </div>
          </div>

          <table class="table" id="tabel-tugas">
            <thead>
              <tr>
                <th>Judul Tugas</th>
                <th>Materi</th>
                <th>Pembuat</th>
                <th>Kelas</th>
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
          <h4 class="modal-title">Hapus Tugas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Tugas ini?</p>
          <input type="hidden" name="tugas_id" id="tugas_id">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataTugas" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Tambah Tugas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="Judul">Judul Tugas</label>
                <input type="text" class="form-control" name="judul" id="addJudul" placeholder="Masukkan Judul Tugas">
              </div>
              <div class="form-group">
                <label for="Kelas">Materi</label>
                <select class="form-control" name="materi" id="addMateri">
                  @foreach($materi as $m)
                  <option value="{{ $m['materi_id'] }}">
                    {{ $m['materi_nama'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat</label>
                <!-- <input type="text" class="form-control" id="Pembuat" placeholder="Masukkan Pembuat Tugas"> -->
                <select class="form-control" name="pembuat" id="addPembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <!-- <input type="text" class="form-control" id="addKelas" placeholder="Masukkan Kelas"> -->
                <select class="form-control" name="kelas" id="addKelas">
                  @foreach($kelas as $k)
                  <option value="{{ $k['kelas_id'] }}">
                    {{ $k['kelas_tingkat'].' '.$k['kelas_nama'].' - '.$k['kelas_tahun_ajaran'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <!-- <div class="form-group">
                <label for="Bsoal">Banyak Soal</label>
                <input type="text" class="form-control" id="addBsoal" placeholder="Masukkan Banyaknya Soal">
              </div> -->
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataTugas" type="button" class="btn btn-success"><a class="text-white">Tambah</a></button>
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
  var tableTugas;

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
    tableTugas = $('#tabel-tugas').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('tugas-management/get-json-tugas') }}",
      columns: [
          { data: 'tugas_judul' },
          { data: 'materi_nama' },
          { data: 'admin_nama_lengkap' },
          { data: null,
            render: function(data, type, row){
              return data.kelas_tingkat+' '+data.kelas_nama+' - '+data.kelas_tahun_ajaran;
          }},
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="{{ url('tugas-management/detail-tugas/`+data.tugas_id+`') }}" class="btn btn-primary" title="Detail"><i class="fas fa-eye"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.tugas_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 3,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataTugas').on('click', function(){
    $.ajax({
      url:"{{ url('tugas-management/tambah-tugas') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Tugas.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Tugas. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Tugas. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#tugas_id').val(id);
  }

  $('#hapusDataTugas').on('click', function(){
    $.ajax({
      url:"{{ url('tugas-management/hapus-tugas') }}",
      method:"POST", 
      data:{tugas_id : $('#tugas_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Tugas.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Tugas. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Tugas. Hubungi Developer!'
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
          tableTugas.ajax.reload();
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