<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Materi;
use Datatables;
use DB;
use Response;

class C_materi_management extends Controller
{
	public function index(){
        session(['navbar' => 'materi']);
		return view('v_materiManagement');
	}

	public function getJsonMateri(){
		return Datatables::of(Materi::select('materi_id', 'materi_nama', 'materi_tingkat', 'materi_detail', 'materi_file'))->make(true);
	}

	public function insertMateri(Request $request){
        // $uploadedFile   = $request->file('file');
        // $path           = $uploadedFile->store('', ['disk' => 'storage_files']); 
        $uploadedFile   = $request->file('file');
        $filename       = 'materi'.time() . '.' . $uploadedFile->getClientOriginalExtension();
        Storage::disk('storage_files')->put($filename, file_get_contents($uploadedFile)); 

		$materi = new Materi();
    	$materi->materi_nama 	= $request->judul;
    	$materi->materi_tingkat = $request->kelas;
    	$materi->materi_detail  = $request->desc;
    	$materi->materi_file 	= '/storage/files/'.$filename;

    	if ($materi->save()) {
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

	public function deleteMateri(Request $request){
        $materi = Materi::select('materi_file')->where('materi_id', '=', $request->materi_id)->first();

		if (Materi::where('materi_id', $request->materi_id)->delete()) {
            File::delete(public_path() . $materi->materi_file);
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

	public function getMateri(Request $request){
		return Response::json([
			'data' => Materi::select('materi_id', 'materi_nama', 'materi_tingkat', 'materi_detail', 'materi_file')->where('materi_id', '=', $request->materi_id)->first()
		]);
	}

	public function updateMateri(Request $request){
		try {
            $dataMateri = array(
                'materi_nama'       => $request->judul,
                'materi_tingkat'    => $request->kelas,
                'materi_detail'     => $request->desc
            );

            if ($request->hasFile('file')) {
                $uploadedFile   = $request->file('file');
                $filename       = 'materi'.time() . '.' . $uploadedFile->getClientOriginalExtension();
                Storage::disk('storage_files')->put($filename, file_get_contents($uploadedFile));
                $dataMateri['materi_file']  = '/storage/files/'.$filename;
            }

    		Materi::where('materi_id', $request->id)
    		  ->update($dataMateri);

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

    public function downloadMateri(Request $request){
        $materi = Materi::where('materi_id', '=', $request->id)->first();
        return Response::download(public_path() .$materi->materi_file);
    }

}
