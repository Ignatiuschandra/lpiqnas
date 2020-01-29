<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class C_dashboard extends Controller
{
	public function index(){
		// if (\Illuminate\Support\Facades\Auth::check())
		// {
		//     echo "login";
		// }else{
		// 	echo "string";
		// }
		// $user = \Illuminate\Support\Facades\Auth::user();
		// var_dump($user->admin_id);
		// var_dump($user->admin_nama_lengkap);
		// var_dump($user->admin_email);
		
		return view('v_dashboard');
	}

}
