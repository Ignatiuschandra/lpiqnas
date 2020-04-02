<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\UjianSoal;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\Notifikasi;
use App\Models\NotifikasiTo;
use Datatables;
use DB;
use Response;

class C_ujian_management extends Controller
{
	public function index(){
        session(['navbar' => 'ujian']);
        $kelas  = Kelas::groupBy('kelas_tingkat', 'kelas_tahun_ajaran')->get();
        $materi = Materi::get();
        // print_r($kelas); exit();
		return view('v_ujianManagement', ['kelas' => $kelas, 'materi' => $materi]);
	}

	public function getJsonUjian(){
		return Datatables::of(
            Ujian::select('ujian_id', DB::Raw('COALESCE(ujian_judul, materi_nama) as ujian_judul'), 'ujian_durasi', 'kelas_tingkat', 'kelas_tahun_ajaran', 'ujian_jadwal', 'admin_nama_lengkap')
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
            try {
                $materi = Materi::where('materi_id', $request->materi)->first();
                $siswa  = Siswa::join('siswa_kelas', 'sk_siswa_id', '=', 'siswa_id')
                            ->join('kelas', 'sk_kelas_id', '=', 'kelas_id')
                            ->where('kelas_tingkat', $materi->materi_tingkat)
                            ->whereNotNull('siswa_fcm_token')
                            ->get('siswa_fcm_token');

                $sendTo = array();
                foreach ($siswa as $key) {
                    $sendTo[] = $key->siswa_fcm_token;
                }

                $dataFCM = array(
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                );

                $notif = array(
                    'title' => 'Ayo Persiapkan Ujian Anda!',
                    'body'  => $request->judul,
                );

                $notifInsert = array(
                    'notif_jenis'   => 'UJIAN',
                    'notif_title'   => 'Ayo Persiapkan Ujian Anda!',
                    'notif_konten'  => $request->judul,
                );

                fcm()
                    ->to($sendTo) // $recipients must an array
                    ->priority('high')
                    ->timeToLive(0)
                    ->data($dataFCM)
                    ->notification($notif)
                ->send();

                $notifId = Notifikasi::insertGetId($notifInsert);

                NotifikasiTo::insert([
                    'nt_notif_id'   => $notifId,
                    'nt_key'        => 'kelas_tingkat',
                    'nt_value'      => $materi->materi_tingkat
                ]);

            } catch (Exception $e) {
                return Response::json([
                    'success'   => false,
                    'data'      => $e->getMessage()
                ]);
            }
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

	public function deleteUjian(Request $request){
        try {
            // TugasSoal::where('ts_tugas_id', $request->tugas_id)->delete();
            Ujian::where('ujian_id', $request->ujian_id)->delete();

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
    public function detailUjian(Request $request){
        return view('v_detailUjian', ['id' => $request->id]);
    }

    public function getJsonUjianSoal(Request $request){
        return Datatables::of(
            UjianSoal::select('us_id', 'us_soal', 'us_gambar', 'us_jawaban', 'us_kunci')
            ->where('us_ujian_id', '=', $request->id)
        )->make(true);
    }

    public function insertSoal(Request $request){
        $jenis = $request->jenis;

        $us = new UjianSoal();
        $us->us_ujian_id        = $request->id;
        $us->us_soal            = $request->soal;

        if ($request->hasFile('gambar')) {
            $us->us_gambar          = base64_encode(file_get_contents($request->file('gambar')));    
        }
        
        $jawaban = (object)array(
            'A' => $request->jawabanA,
            'B' => $request->jawabanB,
            'C' => $request->jawabanC,
            'D' => $request->jawabanD,
            'E' => $request->jawabanE
        );
        $us->us_jawaban = json_encode($jawaban);
        $us->us_kunci   = $request->jawaban;

        if ($us->save()) {
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
        if (UjianSoal::where('us_id', $request->soal_id)->delete()) {
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
            'data' => UjianSoal::select('us_id', 'us_soal', 'us_gambar', 'us_jawaban', 'us_kunci')
            ->where('us_id', '=', $request->soal_id)->first()
        ]);
    }

    public function updateSoal(Request $request){
        try {
            $jawaban = (object)array(
                'A' => $request->jawabanA,
                'B' => $request->jawabanB,
                'C' => $request->jawabanC,
                'D' => $request->jawabanD,
                'E' => $request->jawabanE
            );

            $arrSoal = array(
                'us_soal'       => $request->soal,
                'us_jawaban'    => json_encode($jawaban),
                'us_kunci'      => $request->jawaban
            );

            if ($request->hasFile('gambar')) {
                $arrSoal['us_gambar'] = base64_encode(file_get_contents($request->file('gambar')));    
            }

            UjianSoal::where('us_id', $request->id)
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
