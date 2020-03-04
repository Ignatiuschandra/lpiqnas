@extends('layouts.master')
@section('title_nav', 'LPIQNAS | User Management')
@section('judul_halaman', 'User Management')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">

      <div class="card">
        
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <a href="" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus-square mr-2"></i> Tambah Siswa</a>
            </div>
          </div>
          
          <table id="tabel-siswa" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">Username</th>
                <th scope="col">Kelas</th>
                <th scope="col">Alamat</th>
                <th scope="col">Tanggal Lahir</th>
                <th scope="col">Telepon</th>
                <th scope="col" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">Username</th>
                <th scope="col">Kelas</th>
                <th scope="col">Alamat</th>
                <th scope="col">Tanggal Lahir</th>
                <th scope="col">Telepon</th>
                <th scope="col" class="text-center">Aksi</th>
              </tr>
            </tfoot>
          </table>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- modal hapus -->
  <div class="modal fade" id="modal-hapus">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hapus User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input id="siswa_id" type="hidden" name="siswa_id">
          <p>Apakah Anda Yakin Ingin Menghapus User ini?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataSiswa" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <h4 class="modal-title">Edit Data User</h4>
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
                <label for="NamaLengkap">Nama Lengkap</label>
                <input type="text" class="form-control" name="namaLengkap" id="editNamaLengkap" placeholder="">
              </div>
              <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" name="username" id="editUsername" placeholder="">
              </div>
              <div class="form-group">
                <label for="Alamat">Alamat</label>
                <input type="text" class="form-control" name="alamat" id="editAlamat" placeholder="">
              </div>
              <div class="form-group">
                <label for="Tgl">Tanggal Lahir</label>
                <input type="date" class="form-control" name="dob" id="editTgl" placeholder="">
              </div>
              <div class="form-group">
                <label for="Telepon">Telepon</label>
                <input type="text" class="form-control" name="noTelp" id="editTelepon" placeholder="">
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <select class="form-control" name="kelas" id="editKelas">
                  <option value="">-- Pilih Kelas</option>
                  @foreach($kelas as $k):
                    <option value="{{$k->kelas_id}}">
                      {{ $k->kelas_tingkat.' '.$k->kelas_nama.' - '.$k->kelas_tahun_ajaran }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataSiswa" type="button" class="btn btn-primary toastrEditSuccess">Simpan</button>
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
          <h4 class="modal-title">Tambah Data User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- /.modal form body -->
          <form role="form" id="formTambah">
            <div class="card-body">
              <div class="form-group">
                <label for="NamaLengkap">Nama Lengkap</label>
                <input type="text" class="form-control" name="namaLengkap" id="addNamaLengkap" placeholder="Masukkan Nama Lengkap">
              </div>
              <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" name="username" id="addUsername" placeholder="Masukkan Username">
              </div>
              <div class="form-group">
                <label for="Alamat">Alamat</label>
                <input type="text" class="form-control" name="alamat" id="addAlamat" placeholder="Masukkan Alamat">
              </div>
              <div class="form-group">
                <label for="Tgl">Tanggal Lahir</label>
                <input type="date" class="form-control" name="dob" id="addTgl" placeholder="">
              </div>
              <div class="form-group">
                <label for="Telepon">Telepon</label>
                <input type="text" class="form-control" name="noTelp" id="addTelepon" placeholder="Masukkan Telepon">
              </div>
              <div class="form-group">
                <label for="Kelas">Kelas</label>
                <select class="form-control" name="kelas" id="addKelas">
                  <option value="">-- Pilih Kelas</option>
                  @foreach($kelas as $k):
                    <option value="{{$k->kelas_id}}">
                      {{ $k->kelas_tingkat.' '.$k->kelas_nama.' - '.$k->kelas_tahun_ajaran }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataSiswa" type="button" class="btn btn-primary toastrAddSuccess">Tambah</button>
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
  var tableSiswa;

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
    tableSiswa = $('#tabel-siswa').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('user-management/get-json-siswa') }}",
      columns: [
          { data: 'siswa_nama_lengkap' },
          { data: 'siswa_username' },
          { data: null,
            render: function(data, type, row){
              if (data.kelas_tingkat != null) {
                return data.kelas_tingkat+' '+data.kelas_nama+' - '+data.kelas_tahun_ajaran;
              }

              return '';
            }
          },
          { data: 'siswa_alamat' },
          { data: 'siswa_dob' },
          { data: 'siswa_telepon' },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-edit" onclick="getDataSiswa(`+data.siswa_id+`)"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.siswa_id+`)"><i class="fas fa-trash"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 5,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataSiswa').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/tambah-siswa') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Siswa.'
          });
          tableSiswa.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Siswa. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Siswa. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#siswa_id').val(id);
  }

  $('#hapusDataSiswa').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/hapus-siswa') }}",
      method:"POST", 
      data:{siswa_id : $('#siswa_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Siswa.'
          });
          tableSiswa.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Siswa. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Siswa. Hubungi Developer!'
        });
      }
    });
  });

  function getDataSiswa(id){
    $.ajax({
      url:"{{ url('user-management/get-siswa') }}",
      method:"POST", 
      data:{siswa_id : id},
      success:function(response) {
        $('#editNamaLengkap').val(null);
        $('#editUsername').val(null);
        $('#editAlamat').val(null);
        $('#editTgl').val(null);
        $('#editTelepon').val(null);
        $('#editId').val(null);
        $('#editKelas').val(null);

        $('#editNamaLengkap').val(response.data.siswa_nama_lengkap);
        $('#editUsername').val(response.data.siswa_username);
        $('#editAlamat').val(response.data.siswa_alamat);
        $('#editTgl').val(response.data.siswa_dob);
        $('#editTelepon').val(response.data.siswa_telepon);
        $('#editId').val(response.data.siswa_id);
        $('#editKelas').val(response.data.kelas_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Siswa. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataSiswa').on('click', function(){
    $.ajax({
      url:"{{ url('user-management/update-siswa') }}",
      method:"POST", 
      data:$('#formEdit').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Siswa.'
          });
          tableSiswa.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Siswa. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Siswa. Hubungi Developer!'
        });
      }
    });
  });

  $(function() {
    $('.swalAddSuccess').click(function() {
      
    });
    $('.swalDefaultSuccess').click(function() {
      Toast.fire({
        type: 'success',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });

    $('.swalDefaultInfo').click(function() {
      Toast.fire({
        type: 'info',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultError').click(function() {
      Toast.fire({
        type: 'error',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultWarning').click(function() {
      Toast.fire({
        type: 'warning',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultQuestion').click(function() {
      Toast.fire({
        type: 'question',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });

    $('.toastrAddSuccess').click(function() {
      toastr.success('Berhasil Menambahkan User.')
    });
    $('.toastrEditSuccess').click(function() {
      // toastr.success('Data Berhasil Diubah.')
    });
    $('.toastrDeleteSuccess').click(function() {
      // toastr.success('Data Berhasil Dihapus.')
    });
    $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultInfo').click(function() {
      toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultError').click(function() {
      toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultWarning').click(function() {
      toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastsDefaultDefault').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultTopLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'topLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomRight').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomRight',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultAutohide').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        autohide: true,
        delay: 750,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultNotFixed').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        fixed: false,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultFull').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        icon: 'fas fa-envelope fa-lg',
      })
    });
    $('.toastsDefaultFullImage').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        image: '../../dist/img/user3-128x128.jpg',
        imageAlt: 'User Picture',
      })
    });
    $('.toastsDefaultSuccess').click(function() {
      $(document).Toasts('create', {
        class: 'bg-success', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultInfo').click(function() {
      $(document).Toasts('create', {
        class: 'bg-info', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultWarning').click(function() {
      $(document).Toasts('create', {
        class: 'bg-warning', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultDanger').click(function() {
      $(document).Toasts('create', {
        class: 'bg-danger', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultMaroon').click(function() {
      $(document).Toasts('create', {
        class: 'bg-maroon', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
  });
</script>
@endsection