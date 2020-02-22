<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\Diskusi;
use App\Models\DiskusiKomentar;
use App\Models\Siswa;
use App\Models\VideoBroadcastingKomentar;
use App\Models\TugasJawabanSiswa;
use App\Models\SiswaPembayaran;
use App\Models\SiswaNilai;
use App\Models\SiswaNilaiTugas;
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
        $siswaId = $request->siswa_id;
        if (VideoBroadcastingKomentar::where('vbk_siswa_id', $siswaId)->delete()) {
            if (TugasJawabanSiswa::where('tjs_siswa_id', $siswaId)->delete()) {
                if (SiswaPembayaran::where('sp_siswa_id', $siswaId)->delete()) {
                    if (SiswaNilai::where('sn_siswa_id', $siswaId)->delete()) {
                        if (SiswaNilaiTugas::where('snt_siswa_id', $siswaId)->delete()) {
                            if (Arsip::where('arsip_siswa_id', $siswaId)->delete()) {
                                if (Diskusi::where('diskusi_siswa_id', $siswaId)->delete()) {
                                    if (DiskusiKomentar::where('diskusi_siswa_id', $siswaId)->delete()) {
                                        if (Siswa::where('siswa_id', $siswaId)->delete()) {
                                            return Response::json([
                                                'success'   => true,
                                                'data'      => null
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
		return Response::json([
			'success'	=> false,
			'data'		=> null
		]);
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
