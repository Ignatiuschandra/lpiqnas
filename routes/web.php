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
});