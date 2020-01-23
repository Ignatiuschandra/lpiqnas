<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'C_dashboard@index');

Route::get('/foo', function () {
    if(!file_exists(public_path('storage'))) {
        echo "string";

        $target = 'public_html/api/public';
        $shortcut = 'public_html/api/storage';
        symlink($target, $shortcut);
    }
});

Route::group(['prefix'=>'user-management'], function(){
    Route::get('/', 'C_user_management@index');
    Route::get('/get-json-siswa', 'C_user_management@getJsonSiswa');
    Route::post('/get-siswa', 'C_user_management@getSiswa');
    Route::post('/tambah-siswa', 'C_user_management@insertSiswa');
    Route::post('/hapus-siswa', 'C_user_management@deleteSiswa');
    Route::post('/update-siswa', 'C_user_management@updateSiswa');
});

Route::group(['prefix'=>'materi-management'], function(){
    Route::get('/', 'C_materi_management@index');
    Route::get('/get-json-materi', 'C_materi_management@getJsonMateri');
    Route::post('/get-materi', 'C_materi_management@getMateri');
    Route::post('/tambah-materi', 'C_materi_management@insertMateri');
    Route::post('/hapus-materi', 'C_materi_management@deleteMateri');
    Route::post('/update-materi', 'C_materi_management@updateMateri');
    Route::get('/download/{id}', 'C_materi_management@downloadMateri');
});

Route::group(['prefix'=>'video-management'], function(){
    Route::get('/', 'C_video_management@index');
    Route::get('/get-json-video', 'C_video_management@getJsonVideo');
    Route::post('/get-video', 'C_video_management@getVideo');
    Route::post('/tambah-video', 'C_video_management@insertVideo');
    Route::post('/hapus-video', 'C_video_management@deleteVideo');
    Route::post('/update-video', 'C_video_management@updateVideo');
});

Route::group(['prefix'=>'tugas-management'], function(){
    Route::get('/', 'C_tugas_management@index');
    Route::get('/get-json-tugas', 'C_tugas_management@getJsonTugas');
    Route::post('/get-tugas', 'C_tugas_management@getTugas');
    Route::post('/tambah-tugas', 'C_tugas_management@insertTugas');
    Route::post('/hapus-tugas', 'C_tugas_management@deleteTugas');
    Route::post('/update-tugas', 'C_tugas_management@updateTugas');

    Route::get('/detail-tugas/{id}', 'C_tugas_management@detailTugas');
    Route::get('/get-json-tugas-soal/{id}', 'C_tugas_management@getJsonTugasSoal');
    Route::post('/get-soal', 'C_tugas_management@getSoal');
    Route::post('/tambah-soal', 'C_tugas_management@insertSoal');
    Route::post('/hapus-soal', 'C_tugas_management@deleteSoal');
    Route::post('/update-soal', 'C_tugas_management@updateSoal');
});

Route::group(['prefix'=>'ujian-management'], function(){
    Route::get('/', 'C_ujian_management@index');
    Route::get('/get-json-ujian', 'C_ujian_management@getJsonUjian');
    Route::post('/get-ujian', 'C_ujian_management@getUjian');
    Route::post('/tambah-ujian', 'C_ujian_management@insertUjian');
    Route::post('/hapus-ujian', 'C_ujian_management@deleteUjian');
    Route::post('/update-ujian', 'C_ujian_management@updateUjian');

    Route::get('/detail-ujian/{id}', 'C_ujian_management@detailUjian');
    Route::get('/get-json-ujian-soal/{id}', 'C_ujian_management@getJsonUjianSoal');
    Route::post('/get-soal', 'C_ujian_management@getSoal');
    Route::post('/tambah-soal', 'C_ujian_management@insertSoal');
    Route::post('/hapus-soal', 'C_ujian_management@deleteSoal');
    Route::post('/update-soal', 'C_ujian_management@updateSoal');
});

Route::group(['prefix'=>'jadwal'], function(){
    Route::get('/', 'C_jadwal@index');
    Route::get('/get-json-jadwal', 'C_jadwal@getJsonJadwal');
    Route::post('/get-jadwal', 'C_jadwal@getJadwal');
    Route::post('/tambah-jadwal', 'C_jadwal@insertJadwal');
    Route::post('/hapus-jadwal', 'C_jadwal@deleteJadwal');
    Route::post('/update-jadwal', 'C_jadwal@updateJadwal');
});

Route::group(['prefix'=>'pengumuman'], function(){
    Route::get('/', 'C_pengumuman@index');
    Route::get('/get-json-pengumuman', 'C_pengumuman@getJsonPengumuman');
    Route::post('/get-pengumuman', 'C_pengumuman@getPengumuman');
    Route::post('/tambah-pengumuman', 'C_pengumuman@insertPengumuman');
    Route::post('/hapus-pengumuman', 'C_pengumuman@deletePengumuman');
    Route::post('/update-pengumuman', 'C_pengumuman@updatePengumuman');
});

Route::group(['prefix'=>'diskusi'], function(){
    Route::get('/', 'C_diskusi@index');
    Route::get('/get-json-diskusi', 'C_diskusi@getJsonDiskusi');
    Route::post('/get-diskusi', 'C_diskusi@getDiskusi');
    Route::post('/get-detail-diskusi', 'C_diskusi@getDetailDiskusi');
    Route::post('/tambah-diskusi', 'C_diskusi@insertDiskusi');
    Route::post('/tambah-komentar', 'C_diskusi@insertKomentar');
    Route::post('/hapus-diskusi', 'C_diskusi@deleteDiskusi');
    Route::post('/update-diskusi', 'C_diskusi@updateDiskusi');
});

Route::group(['prefix'=>'review-ujian'], function(){
    Route::get('/', 'C_review_ujian@index');
    Route::get('/get-json-ujian', 'C_review_ujian@getJsonUjian');
    Route::post('/get-ujian', 'C_review_ujian@getUjian');
    Route::post('/tambah-ujian', 'C_review_ujian@insertUjian');
    Route::post('/hapus-ujian', 'C_review_ujian@deleteUjian');
    Route::post('/update-ujian', 'C_review_ujian@updateUjian');

    Route::get('/detail-ujian/{id}', 'C_review_ujian@detailReviewUjian');
    Route::get('/get-json-ujian-detail/{id}', 'C_review_ujian@getJsonUjianDetail');
});