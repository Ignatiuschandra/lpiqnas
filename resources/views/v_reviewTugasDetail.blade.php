@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Review Tugas')
@section('judul_halaman', 'Review Tugas')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          <div class="row">
            <div class="col-md-6 form-inline">
              <h4>Bobot Nilai PG: </h4>
              <input id="bobot" type="text" class="form-control col-3 mb-3 ml-2" placeholder=".." title="Bobot tiap soal PG" value="{{ $bobot }}" {{ $disabled }}>
            </div>
          </div>

          <table class="table text-center" id="tabel-tugas">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Nilai</th>
                <!-- <th style="width: 8%" class="text-center">
                      Status
                  </th> -->
                <th>Aksi</th> 
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
          <h4 class="modal-title">Detail Jawaban</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form role="form">
            <div class="card-body">
             <table class="table" id="table-jawaban">
              <thead>
                <tr>
                  <th>Jawaban Siswa</th>
                  <th>Kunci Jawaban</th>
                  <th>Nilai</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>


          <!-- kalo tugasnya ESSAY 
              <div class="form-group">  
                <label for="Nilai">Nilai</label>
                <input type="text" class="form-control" id="Nilai" placeholder="Masukkan Nilai">
              </div>
          -->
              
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="form-inline"> 
                <label>Nilai Siswa</label>
                <div class="col-3">
                <input id="totalNilai" type="text" class="form-control" placeholder=".." value="0" disabled>
                </div>
          </div>
          Nilai Siswa = (Benar PG x Bobot) + Nilai Essay
          <input type="hidden" id="siswa_id">
          <input type="hidden" id="tugas_id" value="{{$id}}">
          <button id="insert-nilai" type="button" class="btn btn-primary">OK</button>
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

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready( function () {
    tableUjian = $('#tabel-tugas').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ url("review-tugas/get-json-tugas-detail/$id") }}',
      columns: [
          { data: 'siswa_nama_lengkap' },
          { data: null, 
            orderable: false,
            render: function(data, type, row){
              if (data.snt_nilai != null) {
                return data.snt_nilai;
              }else{
                return 0;
              }
            }
           },
          { data: null,
            render: function(data, type, row){
              console.log(data.snt_nilai);
              if (data.snt_nilai != null) {
                return `<td class="text-center py-0 align-middle">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-detail" title="detail" disabled><i class="fas fa-edit"></i></button> 
                  </div>
                </td>`;
              }else{
                return `<td class="text-center py-0 align-middle">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-detail" title="detail" onclick="getJawaban(`+data.siswa_id+`)"><i class="fas fa-edit"></i></button> 
                  </div>
                </td>`;
              }
            },
            orderable: false
          }
      ]
    });
  });

  function getJawaban(idSiswa){
    $("#table-jawaban > tbody").empty();
    $('#totalNilai').val(0);
    $('#siswa_id').val(idSiswa);

    var totalNilai  = 0;
    var html        = '';

    $.ajax({
      url:"{{ url('review-tugas/get-jawaban') }}",
      method:"POST", 
      data:{id : idSiswa},
      success:function(response) {
        if (response.success == true) {
          $.each(response.data, function(i, v){
            if (v.ts_jenis == 'PG') {
              nilai = 0;
              if (v.tjs_jawaban == v.ts_kunci) {
                nilai = $('#bobot').val();
              }
              html += `<tr>
                        <td>`+v.tjs_jawaban+`</td>
                        <td>`+v.ts_kunci+`</td>
                        <td>
                          <input type="text" class="form-control nilai" placeholder=".." disabled value="`+nilai+`">
                        </td>
                      </tr>`;
              totalNilai += nilai;
            }else if(v.ts_jenis == 'ESSAY'){
              html += `<tr>
                        <td>`+v.tjs_jawaban+`</td>
                        <td>`+v.ts_kunci_essay+`</td>
                        <td>
                          <input type="text" class="form-control nilai" placeholder=".." value="0" onkeyup="setNilai()">
                        </td>
                      </tr>`;
            }  
          });

          $('#table-jawaban > tbody').html(html);
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Mengambil Data Jawaban. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Jawaban. Hubungi Developer!'
        });
      }
    });
  }

  function setNilai(){
    nilai = 0;
    $('.nilai').each(function(i, obj) {
      if ($(this).val() != '') {
        nilai += parseInt($(this).val());
      }
    });
    $('#totalNilai').val(nilai);
  }

  $('#insert-nilai').on('click', function(){
    $.ajax({
      url:"{{ url('review-tugas/insert-nilai') }}",
      method:"POST", 
      data:{
        siswa_id  : $('#siswa_id').val(),
        tugas_id  : $('#tugas_id').val(),
        nilai     : $('#totalNilai').val(),
        bobot     : $('#bobot').val()
      },
      success:function(response) {
        if (response.success == true) {
          $('#modal-detail').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda berhasil menambahkan data nilai!'
          });  
          tableUjian.ajax.reload();   
          $('#bobot').attr('disabled', true);       
        }else{
          if (response.info == 'duplicate') {
            Toast.fire({
              type: 'error',
              title: 'Anda Gagal Menambahkan Data Nilai. Nilai sudah pernah dimasukkan!'
            });  
          }else{
            Toast.fire({
              type: 'error',
              title: response.info
            });
          }
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Jawaban. Hubungi Developer!'
        });
      }
    });
  });
</script>
@endsection