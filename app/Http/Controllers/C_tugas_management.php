<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasSoal;
use App\Models\Kelas;
use Datatables;
use DB;
use Response;

class C_tugas_management extends Controller
{
	public function index(){
        $kelas = Kelas::get();
        // print_r($kelas); exit();
		return view('v_tugasManagement', ['kelas' => $kelas]);
	}

	public function getJsonTugas(){
		return Datatables::of(
            Tugas::select('tugas_id', 'tugas_judul', 'kelas_nama', 'kelas_tingkat', 'kelas_tahun_ajaran', 'admin_nama_lengkap')
            ->join('kelas', 'kelas_id', '=', 'tugas_kelas_id')
            ->join('admin', 'admin_id', '=', 'tugas_pembuat_id')
        )->make(true);
	}

	public function insertTugas(Request $request){
		$tugas = new Tugas();
    	$tugas->tugas_judul 	   = $request->judul;
    	$tugas->tugas_pembuat_id   = $request->pembuat;
        $tugas->tugas_kelas_id     = $request->kelas;

    	if ($tugas->save()) {
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

	public function deleteTugas(Request $request){
        try {
            TugasSoal::where('ts_tugas_id', $request->tugas_id)->delete();
            Tugas::where('tugas_id', $request->tugas_id)->delete();

            return Response::json([
                'success'   => true,
                'data'      => null
            ]);
        } catch (Exception $e) {
            return Response::json([
                'success'   => false,
                'data'      => $e->message()
            ]);
        }
	}	

    // detail tugas 
    public function detailTugas(Request $request){
        return view('v_detailTugas', ['id' => $request->id]);
    }

    public function getJsonTugasSoal(Request $request){
        return Datatables::of(
            TugasSoal::select('ts_id', 'ts_soal', 'ts_gambar', 'ts_jawaban', 'ts_kunci', 'ts_jenis')
            ->where('ts_tugas_id', '=', $request->id)
        )->make(true);
    }

}
