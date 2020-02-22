<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\SiswaNilai;
use Datatables;
use DB;
use Response;

class C_review_ujian extends Controller
{
	public function index(){
        session(['navbar' => 'rujian']);
        $kelas  = Kelas::groupBy('kelas_tingkat', 'kelas_tahun_ajaran')->get();
        $materi = Materi::get();
        // print_r($kelas); exit();
		return view('v_reviewUjian', ['kelas' => $kelas, 'materi' => $materi]);
	}

	public function getJsonUjian(){
		return Datatables::of(
            Ujian::select('ujian_id', DB::Raw('COALESCE(ujian_judul, materi_nama) as ujian_judul'), 'materi_nama','ujian_durasi', 'kelas_tingkat', 'kelas_tahun_ajaran', 'ujian_jadwal', 'admin_nama_lengkap')
            ->join('materi', 'materi_id', '=', 'ujian_materi_id')
            ->join('kelas', 'kelas_tingkat', '=', 'materi_tingkat')
            ->join('admin', 'admin_id', '=', 'ujian_pembuat_id')
            ->groupBy('ujian_id')
        )->make(true);
	}

	public function insertUjian(Request $request){
		$ujian = new Ujian();
        $ujian->ujian_materi_id     = $request->materi;
    	$ujian->ujian_judul         = $request->judul;
    	$ujian->ujian_jadwal        = date('Y-m-d', strtotime($request->tanggal)).' '.$request->jam;
        $ujian->ujian_durasi        = $request->durasi;
        $ujian->ujian_pembuat_id    = $request->pembuat;

    	if ($ujian->save()) {
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
    public function detailReviewUjian(Request $request){
        return view('v_reviewUjianDetail', ['id' => $request->id]);
    }

    public function getJsonUjianDetail(Request $request){
        return Datatables::of(
            SiswaNilai::select('siswa_nama_lengkap', 'sn_nilai', 'kelas_tingkat', 'kelas_nama', 'kelas_tahun_ajaran')
            ->join('siswa', 'siswa_id', '=', 'sn_siswa_id')
            ->join('siswa_kelas', 'siswa_id', '=', 'sk_siswa_id')
            ->join('kelas', 'sk_kelas_id', '=', 'kelas_id')
            ->where('sn_ujian_id', '=', $request->id)
        )->make(true);
    }

}
