<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\Kelas;
// use Twilio\Rest\Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Datatables;
use DB;
use Response;

class C_broadcast_wa extends Controller
{
    //specify instance URL and token
    var $APIurl = 'https://eu2.chat-api.com/instance103954/';
    var $token  = 'xm64qo0cxe1rtzkb';

	public function index(){
        session(['navbar' => 'wa']);
        $data['kelas'] = Kelas::all(); 
		return view('v_broadcastWA', $data);
	}

    public function sendWA(Request $request){
        $siswa      = Siswa::join('siswa_kelas', 'sk_siswa_id', '=', 'siswa_id')
                        ->whereNotNull('siswa_telepon')
                        ->where('sk_kelas_id', $request->kelas)
                        ->get('siswa_telepon');

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

    public function sendWAV2(Request $request){
        $siswa  = SiswaKelas::select('siswa_id', 'siswa_nama_lengkap', 
                    'siswa_alamat', 'siswa_username', 'siswa_dob', 'siswa_telepon', 'kelas_tingkat', 
                    'kelas_nama', 'kelas_tahun_ajaran')
                    ->join(DB::Raw("(select max(created_at) as ca from siswa_kelas GROUP BY sk_siswa_id) as ca"), 'ca.ca', '=', DB::Raw('siswa_kelas.created_at'))
                    ->rightJoin('siswa', 'siswa_id', '=', 'sk_siswa_id')
                    ->leftJoin('kelas', 'sk_kelas_id', '=', 'kelas_id')
                    ->where('sk_kelas_id', $request->kelas)
                    ->get();

        $message    = "$request->pesan";
        // print_r($siswa);exit();

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

                $data = array(
                            'phone'   => "$noTelp",
                            'body'    => $message
                        );

                $my_result_object = json_decode($this->sendRequest('sendMessage', $data));
                // print_r($my_result_object);exit();
                if (!$my_result_object->sent) {
                    return Response::json([
                        'success'   => false,
                        'data'      => $my_result_object->message
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

    public function sendRequest($method,$data){
        $url = $this->APIurl.$method.'?token='.$this->token;
        // echo $url;exit();
        if(is_array($data)){ $data = json_encode($data);}
        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data
        ]]);

        $headers = [
            'Content-type: application/json',
        ];

        if (!function_exists('curl_init')){ 
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}
