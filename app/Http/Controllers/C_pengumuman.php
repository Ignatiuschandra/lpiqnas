<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Materi;
use App\Models\Kelas;
use Datatables;
use DB;
use Response;

class C_pengumuman extends Controller
{
	public function index(){
		return view('v_pengumuman');
	}

	public function getJsonPengumuman(){
		return Datatables::of(Pengumuman::select('pengumuman_id', 'pengumuman_judul', 'admin_nama_lengkap', 'pengumuman_konten', 'pengumuman_expired')
                ->join('admin', 'admin_id', '=', 'pengumuman_pembuat_id')
            )->make(true);
	}

	public function insertPengumuman(Request $request){
		$pengumuman = new Pengumuman();
    	$pengumuman->pengumuman_judul	     = $request->judul;
    	$pengumuman->pengumuman_konten 	     = $request->konten;
        $pengumuman->pengumuman_pembuat_id   = $request->pembuat;
        $pengumuman->pengumuman_expired      = $request->tanggal;

    	if ($pengumuman->save()) {
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

	public function deletePengumuman(Request $request){
		if (Pengumuman::where('pengumuman_id', $request->pengumuman_id)->delete()) {
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

	public function getPengumuman(Request $request){
		return Response::json([
			'data' => Pengumuman::select('pengumuman_id', 'pengumuman_judul', 'pengumuman_konten', 'pengumuman_pembuat_id', 'pengumuman_expired')->where('pengumuman_id', '=', $request->pengumuman_id)->first()
		]);
	}

	public function updatePengumuman(Request $request){
		try {
    		Pengumuman::where('pengumuman_id', $request->id)
    		->update([
    			'pengumuman_judul' 	     => $request->judul,
    			'pengumuman_konten' 	 => $request->konten,
                'pengumuman_pembuat_id'  => $request->pembuat,
                'pengumuman_expired'     => $request->tanggal
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
