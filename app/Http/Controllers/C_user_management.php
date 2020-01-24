<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Datatables;
use DB;
use Response;

class C_user_management extends Controller
{
	public function index(){
		return view('v_userManagement');
	}

	public function getJsonSiswa(){
		return Datatables::of(Siswa::select('siswa_id', 'siswa_nama_lengkap', 'siswa_alamat', 'siswa_username', 'siswa_dob', 'siswa_telepon'))->make(true);
	}

	public function insertSiswa(Request $request){
		$siswa = new Siswa();
    	$siswa->siswa_nama_lengkap 	= $request->namaLengkap;
    	$siswa->siswa_telepon 		= $request->noTelp;
    	$siswa->siswa_username 		= $request->username;
    	$siswa->siswa_dob 			= $request->dob;
    	$siswa->siswa_alamat		= $request->alamat;
    	$siswa->siswa_password 		= password_hash('lpiqnas', PASSWORD_DEFAULT);

    	if ($siswa->save()) {
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

	public function deleteSiswa(Request $request){
		if (Siswa::where('siswa_id', $request->siswa_id)->delete()) {
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
    			'siswa_dob' 			=> $request->dob,
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

}
