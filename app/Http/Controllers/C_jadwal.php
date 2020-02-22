<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasJadwal;
use App\Models\Materi;
use App\Models\Kelas;
use Datatables;
use DB;
use Response;

class C_jadwal extends Controller
{
	public function index(){
        session(['navbar' => 'jadwal']);
        $materi = Materi::get();
        $kelas  = Kelas::get();
		return view('v_jadwalKelas', ['materi' => $materi, 'kelas' => $kelas]);
	}

	public function getJsonJadwal(){
		return Datatables::of(KelasJadwal::select('kj_id', 'materi_nama', 'kelas_tingkat', 'kelas_nama', 'kelas_tahun_ajaran', 'kj_hari', 'kj_mulai', 'kj_selesai')
                ->join('materi', 'materi_id', '=', 'kj_materi_id')
                ->join('kelas', 'kelas_id', '=', 'kj_kelas_id')
            )->make(true);
	}

	public function insertJadwal(Request $request){
		$kj = new KelasJadwal();
    	$kj->kj_materi_id	= $request->materi;
    	$kj->kj_kelas_id 	= $request->kelas;
        $kj->kj_hari         = $request->hari;
        $kj->kj_mulai        = $request->mulai;
        $kj->kj_selesai      = $request->selesai;

    	if ($kj->save()) {
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

	public function deleteJadwal(Request $request){
		if (KelasJadwal::where('kj_id', $request->jadwal_id)->delete()) {
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

	public function getJadwal(Request $request){
		return Response::json([
			'data' => KelasJadwal::select('kj_id', 'kj_materi_id', 'kj_kelas_id', 'kj_hari', 'kj_mulai', 'kj_selesai')->where('kj_id', '=', $request->jadwal_id)->first()
		]);
	}

	public function updateJadwal(Request $request){
		try {
    		KelasJadwal::where('kj_id', $request->id)
    		->update([
    			'kj_materi_id' 	=> $request->materi,
    			'kj_kelas_id' 	=> $request->kelas,
                'kj_hari'       => $request->hari,
                'kj_mulai'      => $request->mulai,
                'kj_selesai'    => $request->selesai
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
