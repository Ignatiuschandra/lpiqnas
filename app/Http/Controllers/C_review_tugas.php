<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasJawabanSiswa;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\SiswaNilaiTugas;
use Datatables;
use DB;
use Response;

class C_review_tugas extends Controller
{
	public function index(){
		return view('v_reviewTugas');
	}

	public function getJsonTugas(){
		return Datatables::of(
            Tugas::select('tugas_id', 'tugas_judul', 'kelas_nama', 'kelas_tingkat', 'kelas_tahun_ajaran', 'admin_nama_lengkap')
            ->join('kelas', 'kelas_id', '=', 'tugas_kelas_id')
            ->join('admin', 'admin_id', '=', 'tugas_pembuat_id')
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
    public function detailReviewTugas(Request $request){
        $bobotPG = Tugas::where('tugas_id', $request->id)->value('tugas_bobot_pg');
        $disabled = 'disabled';

        if (empty($bobotPG)) {
            $bobotPG    = 1;
            $disabled   = '';
        }
        return view('v_reviewTugasDetail', ['id' => $request->id, 'bobot' => $bobotPG, 'disabled' => $disabled]);
    }

    public function getJsonTugasDetail(Request $request){
        return Datatables::of(
            TugasJawabanSiswa::select('siswa_nama_lengkap', 'siswa_id', 'snt_nilai')
            ->join('siswa', 'siswa_id', '=', 'tjs_siswa_id')
            ->join('tugas_soal', 'ts_tugas_id', '=', 'tjs_tugas_soal_id')
            ->leftJoin('siswa_nilai_tugas', 'snt_siswa_id', '=', 'siswa_id')
            ->groupBy('ts_tugas_id', 'siswa_id')
            ->where('ts_tugas_id', '=', $request->id)
        )->make(true);
    }

    public function getJawaban(Request $request){
        try {
            $data = TugasJawabanSiswa::select('ts_jenis', 'ts_kunci', 'ts_kunci_essay', 'tjs_jawaban')
                ->join('tugas_soal', 'ts_id', '=', 'tjs_tugas_soal_id')
                ->where('tjs_siswa_id', $request->id)->get();

            return Response::json([
                'success'   => true,
                'data'      => $data
            ]);    
        } catch (Exception $e) {
            return Response::json([
                'success'   => false,
                'data'      => $e->message()
            ]);
        }
        
    }

    public function insertNilai(Request $request){
        try {
            $cek = SiswaNilaiTugas::where('snt_tugas_id', $request->tugas_id)
                ->where('snt_siswa_id', $request->siswa_id)->first();

            // nilai ujian belum ada
            if (empty($cek)) {
                $bobotPG = Tugas::where('tugas_id', $request->tugas_id)->value('tugas_bobot_pg');
                if (empty($bobotPG)) {
                    Tugas::where('tugas_id', $request->tugas_id)
                    ->update([
                        'tugas_bobot_pg'    => $request->bobot,
                    ]);
                }

                $snt = new SiswaNilaiTugas();
                $snt->snt_tugas_id     = $request->tugas_id;
                $snt->snt_siswa_id     = $request->siswa_id;
                $snt->snt_nilai        = $request->nilai;

                if ($snt->save()) {
                    return Response::json([
                        'success'   => true,
                        'info'      => 'inserted',
                        'data'      => null
                    ]);
                }else{
                    return Response::json([
                        'success'   => false,
                        'info'      => 'failed',
                        'data'      => null
                    ]);
                }
            }else{ //data nilai udah ada
                return Response::json([
                    'success'   => false,
                    'info'      => 'duplicate',
                    'data'      => null
                ]);
            }
        } catch (Exception $e) {
            return Response::json([
                'success'   => false,
                'info'      => $e->message(),
                'data'      => null
            ]);
        }
    }

}
