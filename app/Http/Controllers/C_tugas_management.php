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
            TugasSoal::select('ts_id', 'ts_soal', 'ts_gambar', 'ts_jawaban', 'ts_kunci', 'ts_jenis', 'ts_kunci_essay')
            ->where('ts_tugas_id', '=', $request->id)
        )->make(true);
    }

    public function insertSoal(Request $request){
        $jenis = $request->jenis;

        $ts = new TugasSoal();
        $ts->ts_tugas_id        = $request->id;
        $ts->ts_soal            = $request->soal;
        $ts->ts_jenis           = $jenis;
        if ($request->hasFile('gambar')) {
            $ts->ts_gambar          = base64_encode(file_get_contents($request->file('gambar')));    
        }
        
        
        if ($jenis == 'PG') {
            $jawaban = (object)array(
                'A' => $request->jawabanA,
                'B' => $request->jawabanB,
                'C' => $request->jawabanC,
                'D' => $request->jawabanD,
                'E' => $request->jawabanE
            );
            $ts->ts_jawaban = json_encode($jawaban);
            $ts->ts_kunci   = $request->jawaban;
        }else{
            $ts->ts_kunci_essay = $request->jawaban;
        }

        if ($ts->save()) {
            return Response::json([
                'success'   => true,
                'data'      => null
            ]);
        }else{
            return Response::json([
                'success'   => false,
                'data'      => null
            ]);
        }
    }

    public function deleteSoal(Request $request){
        if (TugasSoal::where('ts_id', $request->soal_id)->delete()) {
            return Response::json([
                'success'   => true,
                'data'      => null
            ]);
        }else{
            return Response::json([
                'success'   => false,
                'data'      => null
            ]);
        }
    }

    public function getSoal(Request $request){
        return Response::json([
            'data' => TugasSoal::select('ts_soal', 'ts_jenis', 'ts_jawaban', 'ts_kunci', 'ts_kunci_essay')->where('ts_id', '=', $request->soal_id)->first()
        ]);
    }

    public function updateSoal(Request $request){
        try {
            $jenis = $request->jenis;

            if ($jenis == 'PG') {
                $jawaban = (object)array(
                    'A' => $request->jawabanA,
                    'B' => $request->jawabanB,
                    'C' => $request->jawabanC,
                    'D' => $request->jawabanD,
                    'E' => $request->jawabanE
                );

                $arrSoal = array(
                    'ts_soal'       => $request->soal,
                    'ts_jenis'      => $jenis,
                );

                $arrSoal['ts_jawaban'] = json_encode($jawaban);
                $arrSoal['ts_kunci']   = $request->jawaban;
            }else{
                $arrSoal = array(
                    'ts_soal'       => $request->soal,
                    'ts_jenis'      => $jenis,
                );

                $arrSoal['ts_kunci_essay']   = $request->jawaban;
            }

            if ($request->hasFile('gambar')) {
                $arrSoal['ts_gambar'] = base64_encode(file_get_contents($request->file('gambar')));    
            }

            
            TugasSoal::where('ts_id', $request->id)
                ->update($arrSoal);

            return Response::json([
                'success'   => true,
                'info'      => 'Success insert to DB',
                'data'      => null
            ]);     
        } catch (Exception $e) {
            return Response::json([
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ]); 
        }
    }

}
