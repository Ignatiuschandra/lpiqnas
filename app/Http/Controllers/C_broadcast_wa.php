<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
// use Twilio\Rest\Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Datatables;
use DB;
use Response;

class C_broadcast_wa extends Controller
{
	public function index(){
		return view('v_broadcastWA');
	}

	// public function sendWA(Request $request){
	// 	$siswa = Siswa::whereNotNull('siswa_telepon')->get('siswa_telepon');

 //        $twilioSid    = env('TWILIO_SID');
 //        $twilioToken  = env('TWILIO_TOKEN');

 //        // echo $twilioToken;exit();

 //        $twilio = new Client($twilioSid, $twilioToken);

 //        try {
 //            foreach ($siswa as $key) {
 //                $noTelp = $key->siswa_telepon;
 //                // echo substr($noTelp, 0, 1);
 //                if (substr($noTelp, 0, 1) == "0") {
 //                    $noTelp = substr($noTelp, 1);
 //                    $noTelp = "+62".$noTelp;
 //                }

 //                $twilio->messages
 //                    ->create(
 //                        "$noTelp",
 //                        array(
 //                            "body" => "$request->pesan",
 //                            // "from" => "whatsapp:+14155238886"
 //                            "from" => "+14086874764"
 //                        )
 //                    );
 //            }     
 //            return Response::json([
 //                'success'   => true,
 //                'data'      => null
 //            ]);       
 //        } catch (Exception $e) {
 //            return Response::json([
 //                'success'   => false,
 //                'data'      => null
 //            ]);
 //        }
	// }

    public function sendWA(Request $request){
        $siswa      = Siswa::whereNotNull('siswa_telepon')->get('siswa_telepon');
        $my_apikey  = "PDVM4FGP0EWIB8UPEN5B";
        $message    = "$request->pesan";

        $client     = new Client;

        if ($message == '') {
            return Response::json([
                'success'   => false,
                'data'      => "Pesan tidak boleh kosong!"
            ]); 
        }

        try {
            foreach ($siswa as $key) {
                $noTelp = $key->siswa_telepon;
                // echo substr($noTelp, 0, 1);
                if (substr($noTelp, 0, 1) == "0") {
                    $noTelp = substr($noTelp, 1);
                    $noTelp = "+62".$noTelp;
                }

                if ($noTelp == '') {
                    continue;
                }

                $destination        = "$noTelp";
                $api_url            = "http://panel.rapiwha.com/send_message.php";
                $api_url            .= "?apikey=". urlencode ($my_apikey);
                $api_url            .= "&number=". urlencode ($destination);
                $api_url            .= "&text=". urlencode ($message);   
                $my_result_object   = json_decode(file_get_contents($api_url, false));

                // $request            = $client->get($api_url);
                // $response           = $request->getBody()->getContents();
                // $my_result_object   = json_decode($response);

                if ($my_result_object->result_code != 0 && $my_result_object->result_code != -9) {
                    return Response::json([
                        'success'   => false,
                        'data'      => $noTelp.' : '.$my_result_object->description
                    ]); 
                }
            }     
            return Response::json([
                'success'   => true,
                'data'      => null
            ]); 
        } catch (\Throwable $e) {
            return Response::json([
                'success'   => false,
                'data'      => $e->getMessage()
            ]);
        }
    }

}
