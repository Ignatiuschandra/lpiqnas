<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Concerns\InteractsWithInput;
use Closure;
use App\Models\Siswa;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('lpiqnas-api-key');
        $headers = getallheaders();
        if(empty($token)){
            return response()->json([
                'error' => 'Authorization Header is empty',
                // 'requestPHP' => $token,
                'requestLaravel' => $headers
            ]);
        }

        // //format bearer token : 
        // //Bearer[spasi]randomhashtoken 
        // $pecah_token = explode(" ", $token);
        // if(count($pecah_token) <> 2){
        //     return response()->json([
        //         'error' => 'Invalid Authorization format'
        //     ]);
        // }

        // if(trim($pecah_token[0]) <> 'Bearer'){
        //     return response()->json([
        //         'error' => 'Authorization header must be a Bearer'
        //     ]);
        // }

        // $access_token = trim($pecah_token[1]);
        $access_token = $token;

        //cek apakah access_token ini ada di database atau tidak
        $cek = Siswa::where('siswa_access_token', $access_token)->first();
        if(empty($cek)){
            return response()->json([
                'error' => 'Forbidden : Invalid access token'
            ]);
        }

        //cek apakah access_token expired atau tidak
        if(strtotime($cek->siswa_token_expired) < time()){
            return response()->json([
                'error' => 'Forbidden : Token is already expired. '
            ]);
        }

        return $next($request);
    }
}
