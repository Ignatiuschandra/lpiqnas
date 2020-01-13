<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasSoal;
use Datatables;
use DB;
use Response;

class C_tugas_management extends Controller
{
	public function index(){
		return view('v_tugasManagement');
	}

	public function getJsonTugas(){
		return Datatables::of(
            Tugas::select('tugas_id', 'tugas_judul', 'kelas_nama', 'kelas_tingkat', 'kelas_tahun_ajaran', 'admin_nama_lengkap')
            ->join('kelas', 'kelas_id', '=', 'tugas_kelas_id')
            ->join('admin', 'admin_id', '=', 'tugas_pembuat_id')
        )->make(true);
	}

	public function insertVideo(Request $request){
		$video = new Video();
    	$video->vb_judul 	= $request->judul;
    	$video->vb_link		= $request->link;

    	if ($video->save()) {
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

	public function deleteVideo(Request $request){
		if (Video::where('vb_id', $request->video_id)->delete()) {
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
