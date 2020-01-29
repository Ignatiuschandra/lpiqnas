<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('', 'C_api@index');
Route::post('register', 'C_api@register');
Route::post('login', 'C_api@login');
// Route::post('insertSoal', 'C_api@insertSoal');
// Route::post('insertTugasSoal', 'C_api@insertTugasSoal');

Route::group(['middleware' => 'check-token'], function(){
	Route::post('updateProfile', 'C_api@updateProfile');
	Route::post('getJadwalKelasHariIni', 'C_api@getJadwalKelasHariIni');
	Route::post('getPengumuman', 'C_api@getPengumuman');
	Route::post('getArsip', 'C_api@getArsip');
	Route::post('getDetailMateri', 'C_api@getDetailMateri');
	Route::post('getPembayaran', 'C_api@getPembayaran');
	Route::post('insertArsip', 'C_api@insertArsip');
	Route::post('getJadwalUjianHariIni', 'C_api@getJadwalUjianHariIni');
	Route::post('getKontenUjian', 'C_api@getKontenUjian');
	Route::post('insertNilaiUjian', 'C_api@insertNilaiUjian');
	Route::post('insertDiskusi', 'C_api@insertDiskusi');
	Route::post('insertDiskusiKomentar', 'C_api@insertDiskusiKomentar');
	Route::post('getDiskusi', 'C_api@getDiskusi');
	Route::post('getDiskusiDetail', 'C_api@getDiskusiDetail');
	Route::post('getTugas', 'C_api@getTugas');
	Route::post('getTugasSoal', 'C_api@getTugasSoal');
	Route::post('insertTugasJawaban', 'C_api@insertTugasJawaban');
	Route::post('getVideoBroadcasting', 'C_api@getVideoBroadcasting');
	Route::post('insertVideoBroadcastingKomentar', 'C_api@insertVideoBroadcastingKomentar');
	Route::post('getKomentarVideoBroadcasting', 'C_api@getKomentarVideoBroadcasting');
	Route::post('getDaftarMateri', 'C_api@getDaftarMateri');
	Route::post('getUserProfile', 'C_api@getUserProfile');
	Route::post('getWaktuServer', 'C_api@getWaktuServer');
	Route::post('updatePassword', 'C_api@updatePassword');
	Route::post('updateFoto', 'C_api@updateFoto');
	Route::post('openPengumuman', 'C_api@openPengumuman');
});
