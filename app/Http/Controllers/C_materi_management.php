<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Materi;
use Datatables;
use DB;
use Response;

class C_materi_management extends Controller
{
	public function index(){
		return view('v_materiManagement');
	}

	public function getJsonMateri(){
		return Datatables::of(Materi::select('materi_id', 'materi_nama', 'materi_tingkat', 'materi_detail', 'materi_file'))->make(true);
	}

	public function insertMateri(Request $request){
        $uploadedFile   = $request->file('file');
        $path           = $uploadedFile->store('', ['disk' => 'storage_files']);  

		$materi = new Materi();
    	$materi->materi_nama 	= $request->judul;
    	$materi->materi_tingkat = $request->kelas;
    	$materi->materi_detail  = $request->desc;
    	$materi->materi_file 	= $path;

    	if ($materi->save()) {
    		return Response::json([
    			'success'	=> true,
    			'data'		=> null
    		]);
    	}else{
    		return Response::json([
    			'success'	=> false,
    			'data'		=> null
    		]);
    	}
	}

	public function deleteMateri(Request $request){
		if (Materi::where('materi_id', $request->materi_id)->delete()) {
    		return Response::json([
    			'success'	=> true,
    			'data'		=> null
    		]);
    	}else{
    		return Response::json([
    			'success'	=> false,
    			'data'		=> null
    		]);
    	}
	}	

	public function getSiswa(Request $request){
		return Response::json([
			'data' => Siswa::select('siswa_id', 'siswa_nama_lengkap', 'siswa_alamat', 'siswa_username', 'siswa_dob', 'siswa_telepon')->where('siswa_id', '=', $request->siswa_id)->first()
		]);
	}

	public function updateSiswa(Request $request){
		try {
    		Siswa::where('siswa_id', $request->id)
    		->update([
    			'siswa_nama_lengkap' 	=> $request->namaLengkap,
    			'siswa_alamat' 			=> $request->alamat,
    			'siswa_dob' 			=> $request->tanggalLahir,
    			'siswa_telepon' 		=> $request->noTelp,
    			'siswa_username' 		=> $request->username
    		]);

    		return Response::json([
    			'success' 	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		]);		
    	} catch (Exception $e) {
    		return Response::json([
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		]);	
    	}
	}

    public function downloadMateri(Request $request){
        $materi = Materi::where('materi_id', '=', $request->id)->first();
        return Response::download(public_path() . '/storage/files/'.$materi->materi_file);
    }

}
