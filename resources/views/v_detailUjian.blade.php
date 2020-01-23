@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Detail Ujian')
@section('judul_halaman', 'Detail Ujian')

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
          <input type="hidden" id="soal_id">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button id="hapusDataSoal" type="button" class="btn btn-primary toastrDeleteSuccess">Ya</button>
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
          <form role="form" id="formEdit" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editId">
            <div class="card-body">
              <div class="form-group">
                <label for="Soal">Soal</label>
                <textarea name="soal" id="editSoal" class="form-control" rows="2" placeholder="Masukkan Soal"></textarea>
              </div>
              <div class="form-group">
                <label for="editExampleInputFile">Gambar (Opsional)</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="editExampleInputFile" name="gambar">
                    <label id="addLabelFile" class="custom-file-label" for="editExampleInputFile">Pilih file</label>
                  </div>
                  <div class="input-group-append">
                    <span class="input-group-text" id="">Upload</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="Jawaban">Jawaban</label>
                <div class="form-inline jawaban pg">
                  <label>A.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanA" id="editJawabanA">
                </div>
                <div class="form-inline jawaban pg">
                  <label>B.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanB" id="editJawabanB">
                </div>
                <div class="form-inline jawaban pg">
                  <label>C.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanC" id="editJawabanC">
                </div>
                <div class="form-inline jawaban pg">
                  <label>D.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanD" id="editJawabanD">
                </div>
                <div class="form-inline jawaban pg">
                  <label>E.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanE" id="editJawabanE">
                </div>
                <div class="form-inline jawaban pg">
                  <label>Kunci : </label>
                  <select class="form-control col-3 mb-3" required name="jawaban" id="editKunciPG">
                    <option value="" selected disabled>-- Pilih Kunci</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="updateDataSoal" type="button" class="btn btn-primary">Update</button>
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
          <form role="form" id="formTambah" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $id }}" id="addId">
            <div class="card-body">
              <div class="form-group">
                <label for="Soal">Soal</label>
                <textarea name="soal" id="addSoal" class="form-control" rows="2" placeholder="Masukkan Soal"></textarea>
              </div>
              <div class="form-group">
                <label for="addExampleInputFile">Gambar (Opsional)</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="addExampleInputFile" name="gambar">
                    <label id="addLabelFile" class="custom-file-label" for="addExampleInputFile">Pilih file</label>
                  </div>
                  <div class="input-group-append">
                    <span class="input-group-text" id="">Upload</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="Jawaban">Jawaban</label>
                <div class="form-inline jawaban pg">
                  <label>A.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanA" id="addJawabanA">
                </div>
                <div class="form-inline jawaban pg">
                  <label>B.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanB" id="addJawabanB">
                </div>
                <div class="form-inline jawaban pg">
                  <label>C.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanC" id="addJawabanC">
                </div>
                <div class="form-inline jawaban pg">
                  <label>D.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanD" id="addJawabanD">
                </div>
                <div class="form-inline jawaban pg">
                  <label>E.</label>
                  <input type="text" class="form-control col-4 mb-3" name="jawabanE" id="addJawabanE">
                </div>
                <div class="form-inline jawaban pg">
                  <label>Kunci : </label>
                  <select class="form-control col-3 mb-3" required name="jawaban" id="addKunciPG">
                    <option value="" selected disabled>-- Pilih Kunci</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button id="tambahDataSoal" type="button" class="btn btn-success">Tambah</button>
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
      ajax: '{{ url("ujian-management/get-json-ujian-soal/$id") }}',
      columns: [
          { data: null,
            render: function(data, type, row){
                var jawaban = '';
                raw = JSON.parse(data.us_jawaban.replace(/&quot;/g,'"'));

                $.each(raw, function(i, v){
                  jawaban += '<br>'+i+'. '+v;
                });
                return data.us_soal+jawaban;
            },
            orderable: false
          },
          { data: 'us_gambar',
          render: function(data, type, row){
            if (data !== null) {
              return `<img width="300" src="data:image/jpeg;base64,`+data+`">`;  
            }else{
              return 'No Image';
            }
          }},
          { data: 'us_kunci' 
          },
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="#" class="btn btn-primary" title="Detail" onclick="getDataSoal(`+data.us_id+`)" data-toggle="modal" data-target="#modal-edit"><i class="fas fa-user-edit"></i></a>
              <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus" onclick="setIdHapus(`+data.us_id+`)"><i class="fas fa-trash"></i></a>
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

  $('#tambahDataSoal').on('click', function(){
    var formData = new FormData();
    formData.append('id', $('#addId').val());
    formData.append('soal', $('#addSoal').val());
    formData.append('jenis', $("input[name='jenis']:checked").val());
    formData.append('gambar', $('#addExampleInputFile')[0].files[0]);

    if($('#addKunciPG').val() === "" || $('#addKunciPG').val() === null){
      Toast.fire({
        type: 'warning',
        title: 'Kunci jawaban belum dipilih!'
      });
      return false;
    }

    formData.append('jawabanA', $("#addJawabanA").val());
    formData.append('jawabanB', $("#addJawabanB").val());
    formData.append('jawabanC', $("#addJawabanC").val());
    formData.append('jawabanD', $("#addJawabanD").val());
    formData.append('jawabanE', $("#addJawabanE").val());
    formData.append('jawaban', $("#addKunciPG").val());

    $.ajax({
      url:"{{ url('ujian-management/tambah-soal') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Soal.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Soal. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Soal. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#soal_id').val(id);
  }

  $('#hapusDataSoal').on('click', function(){
    $.ajax({
      url:"{{ url('ujian-management/hapus-soal') }}",
      method:"POST", 
      data:{soal_id : $('#soal_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Soal.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Soal. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Soal. Hubungi Developer!'
        });
      }
    });
  });

  function getDataSoal(id){
    $.ajax({
      url:"{{ url('ujian-management/get-soal') }}",
      method:"POST", 
      data:{soal_id : id},
      success:function(response) {
        raw = JSON.parse(response.data.us_jawaban.replace(/&quot;/g,'"'));

        $.each(raw, function(i, v){
          $('#editJawaban'+i).val(v);
        });

        $("#editKunciPG").val(response.data.us_kunci);
        $('#editSoal').val(response.data.us_soal);
        $('#editId').val(id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Video. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataSoal').on('click', function(){
    var formData = new FormData();
    formData.append('id', $('#editId').val());
    formData.append('soal', $('#editSoal').val());
    formData.append('gambar', $('#editExampleInputFile')[0].files[0]);

    if($('#editKunciPG').val() === "" || $('#editKunciPG').val() === null){
      Toast.fire({
        type: 'warning',
        title: 'Kunci jawaban belum dipilih!'
      });
      return false;
    }
    formData.append('jawabanA', $("#editJawabanA").val());
    formData.append('jawabanB', $("#editJawabanB").val());
    formData.append('jawabanC', $("#editJawabanC").val());
    formData.append('jawabanD', $("#editJawabanD").val());
    formData.append('jawabanE', $("#editJawabanE").val());
    formData.append('jawaban', $("#editKunciPG").val());

    $.ajax({
      url:"{{ url('ujian-management/update-soal') }}",
      method:"POST", 
      data:formData,
      processData: false,
      contentType: false,
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Soal.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Soal. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Soal. Hubungi Developer!'
        });
      }
    });
  });

  // cek masksimal gambar 2MB
  document.getElementById("addExampleInputFile").onchange = function() {
    if(this.files[0].size > 2097152){
        Toast.fire({
          type: 'warning',
          title: 'Maksimal Ukuran Gambar 2MB!'
        });
       this.value = "";
       $('#addLabelFile').text('Pilih file');
    }else{
      $('#addLabelFile').text(this.files[0].name);
    }
  };
</script>
@endsection