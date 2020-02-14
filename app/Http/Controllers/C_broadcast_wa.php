<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Twilio\Rest\Client;
use Datatables;
use DB;
use Response;

class C_broadcast_wa extends Controller
{
	public function index(){
		return view('v_broadcastWA');
	}

	public function sendWA(Request $request){
		$siswa = Siswa::whereNotNull('siswa_telepon')->get('siswa_telepon');

        $twilioSid    = env('TWILIO_SID');
        $twilioToken  = env('TWILIO_TOKEN');

        // echo $twilioToken;exit();

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            foreach ($siswa as $key) {
                // echo $key->siswa_telepon;
                $twilio->messages
                    ->create(
                        "whatsapp:$key->siswa_telepon",
                        array(
                            "body" => "$request->pesan",
                            "from" => "whatsapp:+14155238886"
                        )
                    );
            }     
            return Response::json([
                'success'   => true,
                'data'      => null
            ]);       
        } catch (Exception $e) {
            return Response::json([
                'success'   => false,
                'data'      => null
            ]);
        }
	}

}
