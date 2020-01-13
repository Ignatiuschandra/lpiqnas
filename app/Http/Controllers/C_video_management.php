<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoBroadcasting as Video;
use Datatables;
use DB;
use Response;

class C_video_management extends Controller
{
	public function index(){
		return view('v_videoManagement');
	}

	public function getJsonVideo(){
		return Datatables::of(Video::select('vb_id', 'vb_judul', 'vb_link'))->make(true);
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

	public function getVideo(Request $request){
		return Response::json([
			'data' => Video::select('vb_id', 'vb_judul', 'vb_link')->where('vb_id', '=', $request->video_id)->first()
		]);
	}

	public function updateVideo(Request $request){
		try {
    		Video::where('vb_id', $request->id)
    		->update([
    			'vb_judul' 	=> $request->judul,
    			'vb_link' 	=> $request->link
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
