<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoBroadcasting as Video;
use App\Models\Siswa;
use App\Models\Notifikasi;
use App\Models\NotifikasiTo;
use Datatables;
use DB;
use Response;

class C_video_management extends Controller
{
	public function index(){
        session(['navbar' => 'video']);
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
            try {
                $siswa  = Siswa::whereNotNull('siswa_fcm_token')->get('siswa_fcm_token');

                $sendTo = array();
                foreach ($siswa as $key) {
                    $sendTo[] = $key->siswa_fcm_token;
                }

                $notif = array(
                    'title' => 'Jangan Lewatkan Video Terbaru!',
                    'body'  => $request->judul,
                );

                $notifInsert = array(
                    'notif_jenis'   => 'VIDEO',
                    'notif_title'   => 'Jangan Lewatkan Video Terbaru!',
                    'notif_konten'  => $request->judul,
                );

                fcm()
                    ->to($sendTo) // $recipients must an array
                    ->priority('high')
                    ->timeToLive(0)
                    ->data($notif)
                    ->notification($notif)
                ->send();

                Notifikasi::insert($notifInsert);

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
