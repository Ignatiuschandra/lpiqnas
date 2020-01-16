<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskusi;
use App\Models\DiskusiKomentar;
use Datatables;
use DB;
use Response;

class C_diskusi extends Controller
{
	public function index(){
		return view('v_diskusi');
	}

	public function getJsonDiskusi(){
		return Datatables::of(Diskusi::select('diskusi_id', 'siswa_nama_lengkap', 'diskusi_pertanyaan', 'admin_nama_lengkap')
                ->join('siswa', 'siswa_id', '=', 'diskusi_siswa_id', 'left')
                ->join('admin', 'diskusi_admin_id', '=', 'admin_id', 'left')
            )->make(true);
	}

	public function insertDiskusi(Request $request){
		$diskusi = new Diskusi();
    	$diskusi->diskusi_pertanyaan	 = $request->diskusi;
    	$diskusi->diskusi_admin_id 	     = $request->pembuat;

    	if ($diskusi->save()) {
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

    public function insertKomentar(Request $request){
        $dk = new DiskusiKomentar();
        $dk->dk_komentar     = $request->komentar;
        $dk->dk_admin_id     = $request->pembuat;
        $dk->dk_diskusi_id   = $request->diskusi_id;

        if ($dk->save()) {
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

	public function deleteDiskusi(Request $request){
		try {
            DiskusiKomentar::where('dk_diskusi_id', $request->diskusi_id)->delete();
            Diskusi::where('diskusi_id', $request->diskusi_id)->delete();

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

	public function getDiskusi(Request $request){
		return Response::json([
			'data' => Diskusi::select('diskusi_id', 'diskusi_admin_id', 'diskusi_pertanyaan')
                ->where('diskusi_id', '=', $request->diskusi_id)->first()
		]);
	}

    public function getDetailDiskusi(Request $request){
        return Response::json([
            'data' => Diskusi::select('diskusi_id', 'siswa_nama_lengkap', 'diskusi_pertanyaan', 'admin_nama_lengkap', 'diskusi.created_at')->join('siswa', 'siswa_id', '=', 'diskusi_siswa_id', 'left')
                ->join('admin', 'diskusi_admin_id', '=', 'admin_id', 'left')->where('diskusi_id', '=', $request->diskusi_id)->first(),
            'komentar' => DiskusiKomentar::select('dk_id', 'siswa_nama_lengkap', 'admin_nama_lengkap', 'dk_komentar', 'diskusi_komentar.created_at')->join('siswa', 'siswa_id', '=', 'dk_siswa_id', 'left')
                ->join('admin', 'dk_admin_id', '=', 'admin_id', 'left')->where('dk_diskusi_id', '=', $request->diskusi_id)->get()
        ]);
    }

	public function updateDiskusi(Request $request){
		try {
    		Diskusi::where('diskusi_id', $request->id)
    		->update([
    			'diskusi_pertanyaan' 	 => $request->diskusi,
    			'diskusi_admin_id' 	     => $request->pembuat
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
