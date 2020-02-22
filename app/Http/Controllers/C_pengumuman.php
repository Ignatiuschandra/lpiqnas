<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

        if ($request->hasFile('file')) {
            $uploadedFile   = $request->file('file');
            $fotoName       = 'event'.time() . '.' . $uploadedFile->getClientOriginalExtension();
            Storage::disk('storage_events')->put($fotoName, file_get_contents($uploadedFile));
            $pengumuman->pengumuman_image        = '/storage/events/'.$fotoName;
        }else{
            $pengumuman->pengumuman_image        = null;
        }

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
        $pengumuman = Pengumuman::where('pengumuman_id', $request->pengumuman_id)->first();
		if (Pengumuman::where('pengumuman_id', $request->pengumuman_id)->delete()) {
            File::delete(public_path() . $pengumuman->pengumuman_image);
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
            $dataPengumuman = array(
                'pengumuman_judul'       => $request->judul,
                'pengumuman_konten'      => $request->konten,
                'pengumuman_pembuat_id'  => $request->pembuat,
                'pengumuman_expired'     => $request->tanggal
            );
            if ($request->hasFile('file')) {
                $uploadedFile   = $request->file('file');
                $filename       = 'envet'.time() . '.' . $uploadedFile->getClientOriginalExtension();
                Storage::disk('storage_events')->put($filename, file_get_contents($uploadedFile));
                $dataPengumuman['pengumuman_image']  = '/storage/events/'.$filename;
            }
    		Pengumuman::where('pengumuman_id', $request->id)
    		  ->update($dataPengumuman);

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
