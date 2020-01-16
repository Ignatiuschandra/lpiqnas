@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Posting dan Diskusi')
@section('judul_halaman', 'Posting dan Diskusi')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Diskusi</a>
            </div>
          </div>

          <table class="table" id="tabel-diskusi">
            <thead>
              <tr>
                <th class="text-left">Diskusi</th>
                <th>Pembuat Diskusi</th>
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


  <!-- modal detail -->
  <div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Diskusi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
            <div class="card-body">
              <input type="hidden" name="id" id="dId">
              <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" alt="user image">
                    <span class="username">
                      <a href="#"><span id="dNama"></span></a>
                    </span>
                    <span class="description"><span id="dTanggal"></span></span>
                  </div>
                  <!-- /.user-block -->
                  <p style="margin-bottom: 30px;">
                    <span id="dPertanyaan"></span>
                  </p>

                  <p style="margin-bottom: 20px; text-align: left;">
                    <a href="#" class="link-black text-sm">
                      <i class="fas fa-comments mr-1"></i> <span id="dJKomen"></span> Comment(s)
                    </a>
                  </p>

                  <div id="daftarKomentar">

                  </div>

                  <input autofocus="true" id="komentarAdmin" class="form-control form-control-sm" type="text" placeholder="Type a comment" style="margin-top: 10px;">
            </div>
            </div>
            <!-- /.card-body -->

        </div>
        <div class="modal-footer justify-content-between">
        
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- modal hapus -->
  <div class="modal fade" id="modal-hapus">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hapus Diskusi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <input type="hidden" name="id" id="diskusi_id">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda Yakin Ingin Menghapus Diskusi ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataDiskusi" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Edit Diskusi</h4>
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
                <label for="Judul">Diskusi</label>
                <textarea name="diskusi" id="editDiskusi" class="form-control" rows="2" placeholder="Masukkan Pertanyaan Diskusi"></textarea>
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat Diskusi</label>
                <select class="form-control" id="editPembuat" name="pembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataDiskusi" type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
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
          <h4 class="modal-title">Tambah Diskusi Kelas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="Judul">Diskusi</label>
                <textarea name="diskusi" id="AddDiskusi" class="form-control" rows="2" placeholder="Masukkan Pertanyaan Diskusi"></textarea>
              </div>
              <div class="form-group">
                <label for="Pembuat">Pembuat Diskusi</label>
                <select class="form-control" id="AddPembuat" name="pembuat">
                  <option value="1">Admin LPIQNAS</option>
                </select>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataDiskusi" type="button" class="btn btn-success toastrAddSuccess">Tambah</button>
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
  var tableDiskusi;

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
    tableDiskusi = $('#tabel-diskusi').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('diskusi/get-json-diskusi') }}",
      columns: [
          { data: 'diskusi_pertanyaan' },
          { data: null,
            orderable: false,
            render: function(data, type, row){
              if (data.siswa_nama_lengkap != null) {
                return data.siswa_nama_lengkap;
              }else{
                return data.admin_nama_lengkap;
              }
            }
          },
          { data: null,
            render: function(data, type, row){
              // diskusi dibuat siswa
              if (data.siswa_nama_lengkap != null) {
                return `<div class="btn-group btn-group-sm">
                  <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modal-detail" onclick="getDetailDiskusi(`+data.diskusi_id+`)"><i class="fas fa-eye"></i></a>
                  <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.diskusi_id+`)"><i class="fas fa-trash"></i></a>
                </div>`;
              }else{ // dibuat admin
                return `<div class="btn-group btn-group-sm">
                  <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modal-detail" onclick="getDetailDiskusi(`+data.diskusi_id+`)"><i class="fas fa-eye"></i></a>
                  <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataDiskusi(`+data.diskusi_id+`)"><i class="fas fa-user-edit"></i></a>
                  <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.diskusi_id+`)"><i class="fas fa-trash"></i></a>
                </div>`;
              }
              
            }
          }
      ],
      columnDefs: [{
        targets: 2,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataDiskusi').on('click', function(){
    $.ajax({
      url:"{{ url('diskusi/tambah-diskusi') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Diskusi.'
          });
          tableDiskusi.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Diskusi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Diskusi. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#diskusi_id').val(id);
  }

  $('#hapusDataDiskusi').on('click', function(){
    $.ajax({
      url:"{{ url('diskusi/hapus-diskusi') }}",
      method:"POST", 
      data:{diskusi_id : $('#diskusi_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Diskusi.'
          });
          tableDiskusi.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Diskusi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Diskusi. Hubungi Developer!'
        });
      }
    });
  });

  function getDataDiskusi(id){
    $.ajax({
      url:"{{ url('diskusi/get-diskusi') }}",
      method:"POST", 
      data:{diskusi_id : id},
      success:function(response) {
        $('#editDiskusi').val(response.data.diskusi_pertanyaan);
        $('#editPembuat').val(response.data.diskusi_admin_id);
        $('#editId').val(response.data.diskusi_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Diskusi. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataDiskusi').on('click', function(){
    $.ajax({
      url:"{{ url('diskusi/update-diskusi') }}",
      method:"POST", 
      data:$('#formEdit').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Diskusi.'
          });
          tableDiskusi.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Diskusi. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Diskusi. Hubungi Developer!'
        });
      }
    });
  });

  function getDetailDiskusi(id){
    $.ajax({
      url:"{{ url('diskusi/get-detail-diskusi') }}",
      method:"POST", 
      data:{diskusi_id : id},
      success:function(response) {
        cleanSlide();
        if (response.data.siswa_nama_lengkap != null) {
          $('#dNama').text(response.data.siswa_nama_lengkap);
        }else{
          $('#dNama').text(response.data.admin_nama_lengkap);
        }

        $('#dPertanyaan').text(response.data.diskusi_pertanyaan);
        $('#dTanggal').text(response.data.created_at);
        $('#dJKomen').text(response.komentar.length);
        $('#dId').val(id);

        komentar    = '';
        komentator  = '';
        style       = '';
        $.each(response.komentar, function(i, v){
          if (v.siswa_nama_lengkap != null) {
            komentator = v.siswa_nama_lengkap;
            style       = '';
          }else{
            komentator = v.admin_nama_lengkap;
            style       = 'style="color: #47E5B1;"';
          }

          komentar += `<div class="card-footer card-comments" style="margin-bottom: 10px;">
                    <div class="card-comment">
                      <div class="comment-text">
                        <span class="username" `+style+`>
                          `+komentator+`
                          <span class="text-muted float-right">`+v.created_at+`</span>
                        </span><!-- /.username -->
                        `+v.dk_komentar+`
                      </div>
                    </div>
                    </div>`;
        });

        $('#daftarKomentar').append(komentar);

      },
      error:function(){
        cleanSlide();

        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Diskusi. Hubungi Developer!'
        });
      }
    });
  }

  $('#komentarAdmin').on('keypress', function (e) {
    if(e.keyCode === 13){
      //Disable textbox to prevent multiple submit
      $(this).attr("disabled", "disabled");
      $.ajax({
        url:"{{ url('diskusi/tambah-komentar') }}",
        method:"POST", 
        data:{
          komentar : $(this).val(),
          pembuat : 1,
          diskusi_id : $('#dId').val()
        },
        success:function(response) {
          if (response.success == true) {
            $('#komentarAdmin').val(null);
            Toast.fire({
              type: 'success',
              title: 'Anda Berhasil Menambahkan Data Komentar.'
            });
            getDetailDiskusi($('#dId').val());
          }else{
            Toast.fire({
              type: 'error',
              title: 'Anda Gagal Menambahkan Data Diskusi. Mohon Coba Kembali!'
            });  
          }
        },
        error:function(){
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Diskusi. Hubungi Developer!'
          });
        },
        complete: function(){
          //Enable the textbox again if needed.
          $('#komentarAdmin').removeAttr("disabled");
        }
      });
    }
  });

  function cleanSlide(){
    $('#dId').val(null);
    $('#dNama').text('');
    $('#dPertanyaan').text('');
    $('#dTanggal').text('');
    $('#daftarKomentar').html('');
  }
</script>
@endsection