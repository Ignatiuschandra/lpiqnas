@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Ujian Management')
@section('judul_halaman', 'Ujian Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Ujian</a>
            </div>
          </div>

          <table class="table" id="tabel-ujian">
            <thead>
              <tr>
                <th>Materi</th>
                <th>Pembuat</th>
                <th>Kelas</th>
                <th>Jadwal</th>
                <th>Durasi (Menit)</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td>Ujian Aqidah</td>
                <td>Adri Sinaga</td>
                <td>A</td>
                <td class="text-center py-0 align-middle">
                  <div class="btn-group btn-group-sm">
                    <a href="detail-ujian.html" class="btn btn-primary" title="Detail"><i class="fas fa-eye"></i></a>
                    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" title="Hapus"><i class="fas fa-trash"></i></a>
                  </div>
                </td>
              </tr> -->
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
          <h4 class="modal-title">Hapus Ujian</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Ujian ini?</p>
          <input type="hidden" id="ujian_id">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataUjian" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Tambah Ujian</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
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
                <label for="Judul">Judul Ujian</label>
                <input type="text" class="form-control" name="judul" id="AddJudul" placeholder="Masukkan Judul Ujian">
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat</label>
                <select class="form-control" name="pembuat" id="addPembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <select class="form-control" name="kelas" id="addKelas">
                  @foreach($kelas as $k)
                  <option value="{{ $k['kelas_id'] }}">
                    {{ $k['kelas_tingkat'].' - '.$k['kelas_tahun_ajaran'] }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="AddTanggal">Tanggal Ujian</label>
                <input type="date" class="form-control" name="tanggal" id="AddTanggal" placeholder="Masukkan Tanggal Ujian">
              </div>
              <div class="form-group">
                <label>Jam Ujian</label>
                <input type="text" class="form-control" name="jam" id="addJam" placeholder="Masukkan Jam Mulai">
              </div>
              <div class="form-group">
                <label for="Bsoal">Durasi Ujian (Menit)</label>
                <input type="text" class="form-control" name="durasi" id="AddDurasi" placeholder="Masukkan Durasi Ujian" value="0">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataUjian" type="button" class="btn btn-success"><a href="#" class="text-white">Tambah</a></button>
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
  var tableUjian;

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  $('#addJam').bootstrapMaterialDatePicker({
    date: false,
    format: 'HH:mm'
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready( function () {
    tableUjian = $('#tabel-ujian').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('ujian-management/get-json-ujian') }}",
      columns: [
          { data: 'ujian_judul' },
          { data: 'admin_nama_lengkap' },
          { data: null,
            render: function(data, type, row){
              return data.kelas_tingkat+' - '+data.kelas_tahun_ajaran;
          }},
          { data: 'ujian_jadwal' },
          { data: 'ujian_durasi' },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="{{ url('ujian-management/detail-ujian/`+data.ujian_id+`') }}" class="btn btn-primary" title="Detail"><i class="fas fa-eye"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.ujian_id+`)"><i class="fas fa-trash"></i></a>
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

  $('#tambahDataUjian').on('click', function(){
    $.ajax({
      url:"{{ url('ujian-management/tambah-ujian') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Ujian.'
          });
          tableUjian.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Ujian. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Ujian. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#ujian_id').val(id);
  }

  $('#hapusDataUjian').on('click', function(){
    $.ajax({
      url:"{{ url('ujian-management/hapus-ujian') }}",
      method:"POST", 
      data:{ujian_id : $('#ujian_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Ujian.'
          });
          tableUjian.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Ujian. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Ujian. Hubungi Developer!'
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
          tableUjian.ajax.reload();
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