@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Detail Tugas')
@section('judul_halaman', 'Detail Tugas')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Soal</a>
            </div>
          </div>

          <table class="table" id="tabel-soal">
            <thead>
              <tr>
                <th>Soal</th>
                <th>Gambar (Optional)</th>
                <th>Jawaban</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td>1</td>
                <td>Siapa kah?<br> A. #<br>B. #<br>C. #<br>D. #</td>
                <td><img src="#"></td>
                <td>A. #</td>
                <td class="text-center py-0 align-middle">
                  <div class="btn-group btn-group-sm">
                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit"><i class="fas fa-user-edit"></i></a>
                    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus"><i class="fas fa-trash"></i></a>
                  </div>
                </td> -->
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
          <h4 class="modal-title">Hapus Soal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Soal ini?</p>
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
          <h4 class="modal-title">Edit Soal Ujian</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form">
            <div class="card-body">
              <div class="form-group">
                <label for="no">No.</label>
                <input type="text" class="form-control" id="no" placeholder="">
              </div>
              <div class="form-group">
                <label for="Soal">Soal</label>
                <textarea id="soal" class="form-control" rows="2"></textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Gambar (Opsional)</label>
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
              <div class="form-group">
                <label for="Jawaban">Jawaban</label>
                <input type="text" class="form-control" id="Jawaban" placeholder="">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary"><a href="tambah-ujian.html" class="text-white">Tambah</a></button>
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
          <h4 class="modal-title">Tambah Soal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form">
            <div class="card-body">
              <div class="form-group">
                <label for="no">No.</label>
                <input type="text" class="form-control" id="no" placeholder="Masukkan No">
              </div>
              <div class="form-group">
                <label for="Soal">Soal</label>
                <textarea id="soal" class="form-control" rows="2" placeholder="Masukkan Soal"></textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Gambar (Opsional)</label>
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
              <div class="form-group">
                <label for="Jawaban">Jawaban</label>
                <input type="text" class="form-control" id="Jawaban" placeholder="Masukkan Jawaban">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success"><a href="tambah-ujian.html" class="text-white">Tambah</a></button>
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
    tableTugas = $('#tabel-soal').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ url("tugas-management/get-json-tugas-soal/$id") }}',
      columns: [
          { data: null,
            render: function(data, type, row){
              if (data.ts_jenis === 'ESSAY') {
                return data.ts_soal;  
              }else{
                var jawaban = '';
                raw = JSON.parse(data.ts_jawaban.replace(/&quot;/g,'"'));

                $.each(raw, function(i, v){
                  jawaban += '<br>'+i+'. '+v;
                });
                return data.ts_soal+jawaban;
              }
              
            },
            orderable: false
          },
          { data: 'ts_gambar',
          render: function(data, type, row){
            if (data !== null) {
              return `<img width="300" src="data:image/jpeg;base64,`+data+`">`;  
            }else{
              return 'No Image';
            }
          }},
          { data: 'ts_kunci' },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="detail-tugas.html" class="btn btn-primary" title="Detail"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.ts_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            },
            orderable: false
          }
      ],
      columnDefs: [{
        targets: 3,
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
          tableTugas.ajax.reload();
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
          tableTugas.ajax.reload();
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