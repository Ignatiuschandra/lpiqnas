<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\Diskusi;
use App\Models\DiskusiKomentar;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SiswaKelas;
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
        session(['navbar' => 'user']);
        $kelas  = Kelas::all();
		return view('v_userManagement', ['kelas' => $kelas]);
	}

	public function getJsonSiswa(){
		return Datatables::of(SiswaKelas::select('siswa_id', 'siswa_nama_lengkap', 
                'siswa_alamat', 'siswa_username', 'siswa_dob', 'siswa_telepon', 'kelas_tingkat', 
                'kelas_nama', 'kelas_tahun_ajaran')
                ->join(DB::Raw("(select max(created_at) as ca from siswa_kelas GROUP BY sk_siswa_id) as ca"), 'ca.ca', '=', DB::Raw('siswa_kelas.created_at'))
                ->rightJoin('siswa', 'siswa_id', '=', 'sk_siswa_id')
                ->leftJoin('kelas', 'sk_kelas_id', '=', 'kelas_id'))->make(true);
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
            $ks = new SiswaKelas();
            $ks->sk_siswa_id = $siswa->id;
            $ks->sk_kelas_id = $request->kelas;
            $ks->save();

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
			'data' => SiswaKelas::select('siswa_id', 'siswa_nama_lengkap', 
                'siswa_alamat', 'siswa_username', 'siswa_dob', 'siswa_telepon', 'kelas_id', 'kelas_tingkat', 
                'kelas_nama', 'kelas_tahun_ajaran')
                ->join(DB::Raw("(select max(created_at) as ca from siswa_kelas GROUP BY sk_siswa_id) as ca"), 'ca.ca', '=', DB::Raw('siswa_kelas.created_at'))
                ->rightJoin('siswa', 'siswa_id', '=', 'sk_siswa_id')
                ->leftJoin('kelas', 'sk_kelas_id', '=', 'kelas_id')
                ->where('siswa_id', '=', $request->siswa_id)
                ->first()
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

            // cari tingkat kelas yang baru
            $kelasBaru = Kelas::where('kelas_id', $request->kelas)->first();

            //cari tingkat kelas siwa yang sederajat
            $kelas = SiswaKelas::join('kelas', 'kelas_id', '=', 'sk_kelas_id')
                        ->where('sk_siswa_id', '=', $request->id)
                        ->where('kelas_tingkat', $kelasBaru->kelas_tingkat)
                        ->first();

            // kalo kosong berarti ganti tingkat / naik kelas
            // maka insert
            if (empty($kelas)) {
                $ks = new SiswaKelas();
                $ks->sk_siswa_id = $request->id;
                $ks->sk_kelas_id = $request->kelas;
                $ks->save();
            }else{ // kalau ada maka update aja
                SiswaKelas::where('sk_id', $kelas->sk_id)
                    ->update([
                        'sk_kelas_id'  => $request->kelas
                    ]);
            }

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
