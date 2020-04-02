<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\SiswaPembayaran;
use App\Models\SiswaPengumuman;
use App\Models\KelasJadwal;
use App\Models\Pengumuman;
use App\Models\Arsip;
use App\Models\Materi;
use App\Models\Pembayaran;
use App\Models\Ujian;
use App\Models\UjianSoal;
use App\Models\SiswaNilai;
use App\Models\Diskusi;
use App\Models\DiskusiKomentar;
use App\Models\Tugas;
use App\Models\TugasSoal;
use App\Models\TugasJawabanSiswa;
use App\Models\VideoBroadcasting;
use App\Models\VideoBroadcastingKomentar;
use App\Models\Notifikasi;
use App\Models\NotifikasiOpen;
use App\Mail\ActivationEmail;
use Validator;
use DB;

class C_api extends Controller
{
	public function index(){
		echo "LPIQNAS API is ONLINE!";
	}

    public function confirmEmail(Request $request){
        $validate = Validator::make($request->all(), [
            'token'   => 'string'
        ]);

        if ($validate->fails()) {
            $data = [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
            return view('email.v_failed', $data);
        }

        $siswa = Siswa::where('siswa_register_token', $request->token)->first();
        if (empty($siswa)) { //token tidak ada atau user tidak ada
            $data = [
                'success'   => false,
                'info'      => 'Token Not Found',
                'data'      => null
            ];
            return view('email.v_failed', $data);
        }else{ //token ada
            try {
                Siswa::where('siswa_register_token', $request->token)
                    ->update([
                        'siswa_is_verified' => 1
                    ]);

                return view('email.v_confirmed');   
            } catch (Exception $e) {
                $data = [
                    'success'   => false,
                    'info'      => $e->getMessage(),
                    'data'      => null
                ];

                return view('email.v_failed', $data);
            }
        }
    }

    public function resendEmail(Request $request){
        $validate = Validator::make($request->all(), [
            'email'         => 'required|email'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            $token = base64_encode(sha1(rand(1, 10000) . uniqid() . time()));
            Siswa::where('siswa_email', $request->email)
                ->update([
                    'siswa_token' => $token
                ]);

            Mail::to($request->email)->send(new ActivationEmail($token));

            return view('email.v_resend');   
        } catch (Exception $e) {
            $data = [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];

            return view('email.v_failed', $data);
        }
    }

	public function register(Request $request){
    	$validate = Validator::make($request->all(), [
    		'namaLengkap' 	=> 'required|string',
    		'noTelp' 		=> 'required|string',
            'email'         => 'required|email',
    		'username' 		=> 'required|string',
    		'password'		=> 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

        //cek username
        $cek_user = Siswa::where('siswa_username', $request->username)->first();
        if(!empty($cek_user)){
            return [
                'success'   => false,
                'info'      => 'Username already taken',
                'data'      => null
            ];
        }

        $token = base64_encode(sha1(rand(1, 10000) . uniqid() . time()));

    	$siswa = new Siswa();
    	$siswa->siswa_nama_lengkap 	  = $request->namaLengkap;
    	$siswa->siswa_telepon 		  = $request->noTelp;
        $siswa->siswa_email           = $request->email;
    	$siswa->siswa_username 		  = $request->username;
    	$siswa->siswa_password 		  = password_hash($request->password, PASSWORD_DEFAULT);
        $siswa->siswa_register_token  = $token;

    	if ($siswa->save()) {
            Mail::to($siswa->siswa_email)->send(new ActivationEmail($token));
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}

	}

    public function login(Request $request){
    	$validate = Validator::make($request->all(), [
    		'username'  => 'required|string',
    		'password'  => 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success'	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	//cek username
    	$cek_user = Siswa::where('siswa_username', $request->username)
                        ->orWhere('siswa_email', $request->username)->first();
    	if(empty($cek_user)){
    		return [
    			'success'	=> false,
    			'info' 		=> 'Username or email not found',
    			'data' 		=> null
    		];
    	}

        //cek is verified
        if($cek_user->siswa_is_verified == 0){
            return [
                'success'   => false,
                'info'      => 'User not verified',
                'data'      => null
            ];
        }

    	//cek password
    	if(!password_verify($request->password, $cek_user->siswa_password)){
    		return [
    			'success'	=> false,
    			'info' 		=> 'Invalid password provided',
    			'data' 		=> null
    		];
    	}

    	//username dan password sudah benar. 
    	$token_instance = $this->generateToken($cek_user);
    	return [
    		'suceess'	=> true,
    		'info' 		=> 'Login success',
    		'data' 		=> [
    			'access_token' 	=> $token_instance,
    			'siswa_id' 		=> $cek_user->siswa_id
    		]
    	];
    }

    protected function generateToken(Siswa $siswa){
    	//generate custom hash sebagai auth token
    	$generated_token = base64_encode(sha1(rand(1, 10000) . uniqid() . time()));
    	//manage token ini akan expired dalam jangka waktu berapa lama
    	$expired = date('Y-m-d H:i:s', strtotime('+1 day'));

    	//proses simpan token ke database
    	Siswa::where('siswa_id', $siswa->siswa_id)
    		->update([
    			'siswa_access_token' 	=> $generated_token,
    			'siswa_token_expired' 	=> $expired
    		]);

    	//setelah token direcord ke database, kembalikan nilai token ke response
    	return $generated_token;
    }

    public function updateProfile(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'namaLengkap' 	=> 'required|string',
    		'alamat'		=> 'nullable|string',
    		'tanggalLahir'	=> 'date_format:Y-m-d|before:today|nullable',
    		'noTelp' 		=> 'required|string',
    		'username' 		=> 'required|string',
    		// 'password'		=> 'required|string|same:password2',
    		// 'password2'		=> 'required|string',
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		Siswa::where('siswa_id', $request->idSiswa)
    		->update([
    			'siswa_nama_lengkap' 	=> $request->namaLengkap,
    			'siswa_alamat' 			=> $request->alamat,
    			'siswa_dob' 			=> $request->tanggalLahir,
    			'siswa_telepon' 		=> $request->noTelp,
    			'siswa_username' 		=> $request->username,
    			// 'siswa_password' 		=> password_hash($request->password, PASSWORD_DEFAULT)
    		]);

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function updatePassword(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'passwordBaru'  => 'required|string',
            'passwordLama'  => 'required|string'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        $cek_user = Siswa::where('siswa_id', $request->idSiswa)->first();

        //cek password lama
        if(!password_verify($request->passwordLama, $cek_user->siswa_password)){
            return [
                'success'   => false,
                'info'      => 'Invalid old password provided',
                'data'      => null
            ];
        }

        try {
            Siswa::where('siswa_id', $request->idSiswa)
                ->update([
                    'siswa_password'        => password_hash($request->passwordBaru, PASSWORD_DEFAULT)
                ]);

            return [
                'success'   => true,
                'info'      => 'Success update to DB',
                'data'      => null
            ];      
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function updateFoto(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'foto'          => 'required|string'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            Siswa::where('siswa_id', $request->idSiswa)
                ->update([
                    'siswa_foto'    => $request->foto
                ]);

            return [
                'success'   => true,
                'info'      => 'Success insert to DB',
                'data'      => null
            ];      
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function updateFCMToken(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'FCMToken'      => 'required|string'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            Siswa::where('siswa_id', $request->idSiswa)
                ->update([
                    'siswa_fcm_token'    => $request->FCMToken
                ]);

            return [
                'success'   => true,
                'info'      => 'Success update to DB',
                'data'      => null
            ];      
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function getJadwalKelasHariIni(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['jadwal'] = KelasJadwal::select('materi_id','materi_nama', 'kj_mulai', 'kj_selesai', 'kelas_nama')
	    		->join('kelas', 'kelas_id', '=', 'kj_kelas_id')
	    		->join('materi', 'materi_id', '=', 'kj_materi_id')
	    		->join('siswa_kelas', 'sk_kelas_id', '=', 'kelas_id')
	    		->where('sk_siswa_id', '=', $request->idSiswa)
                ->whereRaw("siswa_kelas.created_at = (select 
                                max(created_at) from siswa_kelas
                                where sk_siswa_id = $request->idSiswa)")
	    		->where('kj_hari', '=', date('N'))->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getPengumuman(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

    	try {
            $data = array();
    		$pengumuman = Pengumuman::select('pengumuman_id', 'pengumuman_judul', 'pengumuman_konten', 'pengumuman_image', DB::raw('admin_nama_lengkap as pengumuman_pembuat'), DB::raw('DATE(pengumuman.created_at) as pengumuman_tanggal'), DB::Raw('COALESCE(siswa_pengumuman.created_at, 0) as status_buka'))
	    		->join('admin', 'pengumuman_pembuat_id', '=', 'admin.admin_id')
                // ->leftJoin('siswa_pengumuman', 'sp_pengumuman_id', '=', 'pengumuman_id')
                ->leftJoin('siswa_pengumuman', function($join) use ($request){
                        $join->on('sp_pengumuman_id', '=', 'pengumuman_id'); 
                        $join->on('sp_siswa_id', '=', DB::raw($request->idSiswa)); 
                   })
	    		->where('pengumuman_expired', '>=', date('Y-m-d'))->get();

            // print_r($pengumuman);
            foreach ($pengumuman as $p) {
                $p->pengumuman_image = url('/').$p->pengumuman_image;
                if ($p->status_buka == 0) {
                    $p->pengumuman_buka = 0;
                }else{
                    $p->pengumuman_buka = 1;
                }

                unset($p->status_buka);

                $data['pengumuman'][] = $p;
            }

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function openPengumuman(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'idPengumuman'  => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            // avoid duplicate
            $cek = SiswaPengumuman::where('sp_siswa_id', $request->idSiswa)
                    ->where('sp_pengumuman_id', $request->idPengumuman)->first();

            if (!empty($cek)) {
                return [
                    'success'   => true,
                    'info'      => 'Data already exist',
                    'data'      => null
                ];
            }

            SiswaPengumuman::insert([
                'sp_siswa_id'       => $request->idSiswa,
                'sp_pengumuman_id'  => $request->idPengumuman
            ]);

            return [
                'success'   => true,
                'info'      => 'Success insert data to DB',
                'data'      => null
            ];
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];   
        }
    }       

    public function getArsip(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['arsip'] = Arsip::select('materi_id', 'materi_nama')
	    		->join('materi', 'materi_id', '=', 'arsip.arsip_materi_id')
	    		->where('arsip_siswa_id', '=', $request->idSiswa)->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertArsip(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'idMateri'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {

    		$is_exist = Arsip::where('arsip_siswa_id', $request->idSiswa)
    			->where('arsip_materi_id', $request->idMateri)->first();

    		if ($is_exist) {
    			return [
	    			'success' 	=> true,
	    			'info' 		=> 'Data already exist in DB',
	    			'data'		=> null
	    		];
    		}else{
    			Arsip::insert([
	    			'arsip_siswa_id' 	=> $request->idSiswa,
	    			'arsip_materi_id' 	=> $request->idMateri
	    		]);

	    		return [
	    			'success' 	=> true,
	    			'info' 		=> 'Success insert data to DB',
	    			'data'		=> null
	    		];
    		}
    				
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function removeArsip(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'idMateri'      => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            Arsip::where('arsip_siswa_id', $request->idSiswa)
                ->where('arsip_materi_id', $request->idMateri)->delete();

            return [
                'success'   => true,
                'info'      => 'Success remove data from DB',
                'data'      => null
            ];
                    
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function getDetailMateri(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idMateri'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$materi = Materi::select('materi_nama', 'materi_detail', 'materi_file')
	    		->where('materi_id', '=', $request->idMateri)->first();

            if (empty($materi)) {
                return [
                    'success'   => true,
                    'info'      => 'Success get data from DB',
                    'data'      => null
                ];    
            }

            $materi = $materi->toArray();

            foreach ($materi as $key => $value) {
                if ($key == 'materi_file') {
                    $val_temp = $value;
                    unset($materi['materi_file']);
                    $url = url('/');
                    $materi['materi_file'] = $url.$val_temp;
                }
            }

            $data['materi'] = $materi;
    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getPembayaran(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		// $data['pembayaran'] = Pembayaran::select('pembayaran_id', 'pembayaran_judul', 'pembayaran_jadwal', 'pembayaran_tagihan')
    		// 	->leftJoin('siswa_pembayaran', 'pembayaran_id', '=', 'sp_pembayaran_id')
    		// 	->where('sp_siswa_id', '=', $request->idSiswa)
    		// 	->orWhere('sp_siswa_id', '=', NULL)
    		// 	->groupBy('sp_pembayaran_id')
	    	// 	->havingRaw('COALESCE(SUM(sp_jumlah),0) < pembayaran_tagihan')->get();

	    	$pembayaran = Pembayaran::select('pembayaran_id', 'pembayaran_judul', 'pembayaran_jadwal', 'pembayaran_tagihan')->get();

	    	// cari data pembayaran siswa
	    	foreach ($pembayaran as $p) {
	    		$jumlah = SiswaPembayaran::select(DB::Raw('COALESCE(SUM(sp_jumlah),0) as JUMLAH'))
	    					->where('sp_siswa_id', '=', $request->idSiswa)
	    					->where('sp_pembayaran_id', '=', $p->pembayaran_id)
	    					->groupBy('sp_pembayaran_id')->first();

	    		if (isset($jumlah->JUMLAH)) {
	    			// ditagih hanya jika kurang
                    $persen = ($jumlah->JUMLAH / $p->pembayaran_tagihan) * 100;
                    $p->pembayaran_terbayar         =  $jumlah->JUMLAH;
                    $p->pembayaran_persentase_lunas =  $persen;
	    		}else{ //belum bayar
                    $p->pembayaran_terbayar         =  0;
                    $p->pembayaran_persentase_lunas =  0;
	    		}

                $data['pembayaran'][] = $p;
	    	}

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getJadwalUjianHariIni(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['jadwal'] = Ujian::select('ujian_id', DB::Raw('COALESCE(ujian_judul, materi_nama) as ujian_judul'), 'ujian_deskripsi', DB::Raw('ujian_durasi as ujian_durasi_menit'))
    			->join('materi','ujian_materi_id', '=', 'materi_id')
    			->join('kelas','materi_tingkat', '=', 'kelas_tingkat')
    			->join('siswa_kelas','sk_kelas_id', '=', 'kelas_id')
                ->leftJoin('siswa_nilai','sn_ujian_id', '=', 'ujian_id')
                ->where('sn_nilai', null)
    			->where('ujian_jadwal', 'LIKE', date('Y-m-d').'%')
	    		->where('sk_siswa_id', '=', $request->idSiswa)
                ->whereRaw("siswa_kelas.created_at = (select 
                                max(created_at) from siswa_kelas
                                where sk_siswa_id = $request->idSiswa)")
                ->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertSoal(Request $request){
    	$jawaban = array(
    		'A' => $request->ja, 
    		'B' => $request->jb, 
    		'C' => $request->jc, 
    		'D' => $request->jd
    	);

    	try {
    		$data['jadwal'] = UjianSoal::insert([
    			'us_soal' 		=> $request->soal,
    			'us_gambar' 	=> $request->gambar,
    			'us_jawaban' 	=> json_encode($jawaban),
    			'us_kunci' 		=> $request->kunci,
    			'us_pembuat_id' => 1,
    			'us_ujian_id'	=> 1
    		]);

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertTugasSoal(Request $request){
    	$jawaban = array(
    		'A' => $request->ja, 
    		'B' => $request->jb, 
    		'C' => $request->jc, 
    		'D' => $request->jd
    	);

    	try {
    		$data['jadwal'] = TugasSoal::insert([
    			'ts_soal' 			=> $request->soal,
    			'ts_gambar' 		=> $request->gambar,
    			'ts_jawaban' 		=> json_encode($jawaban),
    			'ts_kunci' 			=> $request->kunci,
    			'ts_kunci_essay' 	=> $request->kunci_essay,
    			'ts_pembuat_id' 	=> 1,
    			'ts_tugas_id'		=> $request->tugas_id
    		]);

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getKontenUjian(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idUjian'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['kontenUjian'] = UjianSoal::select('us_id', 'us_soal', 'us_gambar','us_jawaban', 'us_kunci')
    			->where('us_ujian_id', $request->idUjian)->get();
    		$data['durasiUjian'] = Ujian::select('ujian_durasi')
    			->where('ujian_id', $request->idUjian)->first()->ujian_durasi;

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertNilaiUjian(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idUjian'		=> 'required|numeric',
    		'idSiswa'		=> 'required|numeric',
    		'nilai'			=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	$is_exist = SiswaNilai::where('sn_siswa_id', $request->idSiswa)
    			->where('sn_ujian_id', $request->idUjian)->first();

		if ($is_exist) {
			return [
    			'success' 	=> true,
    			'info' 		=> 'Data already exist in DB',
    			'data'		=> null
    		];
		}

    	$sn = new SiswaNilai();
    	$sn->sn_siswa_id 	= $request->idSiswa;
    	$sn->sn_ujian_id 	= $request->idUjian;
    	$sn->sn_nilai 		= $request->nilai;

    	if ($sn->save()) {
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}
    }

    public function insertDiskusi(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'pertanyaan'	=> 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	$d = new Diskusi();
    	$d->diskusi_siswa_id 	= $request->idSiswa;
    	$d->diskusi_pertanyaan 	= $request->pertanyaan;

    	if ($d->save()) {
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}
    }

    public function insertDiskusiKomentar(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'idDiskusi'		=> 'required|numeric',
    		'komentar'		=> 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	$dk = new DiskusiKomentar();
    	$dk->dk_siswa_id 	= $request->idSiswa;
    	$dk->dk_diskusi_id 	= $request->idDiskusi;
    	$dk->dk_komentar 	= $request->komentar;

    	if ($dk->save()) {
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}
    }

    public function getDiskusi(Request $request){
    	try {
    		$diskusiRaw = Diskusi::select('diskusi_id', 'diskusi_pertanyaan', DB::Raw('SUM(dk_admin_id) as diskusi_dijawab_admin'))
                            ->leftJoin('diskusi_komentar', 'diskusi_id', '=', 'dk_diskusi_id')
                            ->groupBy('diskusi_id')->get();

    		foreach ($diskusiRaw as $key) {
    			if (!is_null($key->diskusi_dijawab_admin)) {
    				$key->diskusi_dijawab_admin = true;
    			}else{
    				$key->diskusi_dijawab_admin = false;
    			}
    		}

    		$data['diskusi'] = $diskusiRaw;

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getDiskusiDetail(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idDiskusi'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['diskusi'] = Diskusi::select('diskusi_id', 'diskusi_pertanyaan')
                                ->where('diskusi_id', $request->idDiskusi)->first();
			$komentar = DiskusiKomentar::
                        select('siswa_nama_lengkap', 'admin_nama_lengkap', DB::Raw('dk_komentar as komentar'), 'diskusi_komentar.created_at')
                        ->where('dk_diskusi_id', $request->idDiskusi)
                        ->leftJoin('siswa', 'dk_siswa_id', '=', 'siswa_id')
                        ->leftJoin('admin', 'dk_admin_id', '=', 'admin_id')->get();

            $data['diskusiKomentar'] = array();

            foreach ($komentar as $key) {
                if (!is_null($key->siswa_nama_lengkap)) {
                    $key->komentator = $key->siswa_nama_lengkap;
                }else if(!is_null($key->admin_nama_lengkap)){
                    $key->komentator = $key->admin_nama_lengkap;
                }
                unset($key->siswa_nama_lengkap, $key->admin_nama_lengkap);
                $data['diskusiKomentar'][] = $key;
            }

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getTugas(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['tugas'] = Tugas::select('tugas_id', 'tugas_judul')
	    		->join('siswa_kelas','tugas_kelas_id', '=', 'sk_kelas_id')
                ->leftJoin('tugas_soal','tugas_id', '=', 'ts_tugas_id')
                ->leftJoin('tugas_jawaban_siswa', function($join){
                    $join->on('tjs_siswa_id', '=', 'sk_siswa_id');
                    $join->on('tjs_tugas_soal_id', '=', 'ts_tugas_id');
                })
                ->where('tjs_id', '=', null)
	    		->where('sk_siswa_id', '=', $request->idSiswa)
                ->whereRaw("siswa_kelas.created_at = (select 
                                max(created_at) from siswa_kelas
                                where sk_siswa_id = $request->idSiswa)")
                ->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getTugasSoal(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idTugas'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['tugasSoal'] = TugasSoal::select('ts_id', 'ts_jenis', 'ts_soal', 'ts_gambar', 'ts_jawaban', 'ts_kunci', 'ts_kunci_essay')->where('ts_tugas_id', '=', $request->idTugas)->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertTugasJawaban(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'idSoal'		=> 'required|numeric',
    		'jawaban'		=> 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	$tjs = new TugasJawabanSiswa();
    	$tjs->tjs_siswa_id 		= $request->idSiswa;
    	$tjs->tjs_tugas_soal_id = $request->idSoal;
    	$tjs->tjs_jawaban 		= $request->jawaban;

    	if ($tjs->save()) {
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}
    }

    public function getVideoBroadcasting(Request $request){
    	try {
    		$data['videoBroadcasting'] = VideoBroadcasting::select('vb_judul', 'vb_link')->get();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function insertVideoBroadcastingKomentar(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idSiswa'		=> 'required|numeric',
    		'idVideo'		=> 'required|numeric',
    		'komentar'		=> 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	$vbk = new VideoBroadcastingKomentar();
    	$vbk->vbk_siswa_id 				= $request->idSiswa;
    	$vbk->vbk_video_broadcasting_id = $request->idVideo;
    	$vbk->vbk_komentar 				= $request->komentar;

    	if ($vbk->save()) {
    		return [
    			'success'	=> true,
    			'info' 		=> 'Success insert to DB',
    			'data'		=> null
    		];
    	}else{
    		return [
    			'success'	=> false,
    			'info' 		=> 'Failed insert to DB',
    			'data'		=> null
    		];
    	}
    }

    public function getKomentarVideoBroadcasting(Request $request){
    	$validate = Validator::make($request->all(), [
    		'idVideo'		=> 'required|numeric'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	try {
    		$data['komentar'] = VideoBroadcastingKomentar::select('siswa_nama_lengkap', 'vbk_komentar', 'video_broadcasting_komentar.created_at')->join('siswa', 'vbk_siswa_id', '=', 'siswa_id')
	    		->latest('vbk_id')->first();

    		return [
    			'success' 	=> true,
    			'info' 		=> 'Success get data from DB',
    			'data'		=> $data
    		];		
    	} catch (Exception $e) {
    		return [
    			'success' 	=> false,
    			'info' 		=> $e->getMessage(),
    			'data'		=> null
    		];	
    	}
    }

    public function getDaftarMateri(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            $materi = Materi::select('materi_id', 'materi_nama', DB::Raw('arsip_id'))
                        ->join('kelas', 'kelas_tingkat', '=', 'materi_tingkat')
                        ->join('siswa_kelas', 'sk_kelas_id', '=', 'kelas_id')
                        ->join('siswa', 'siswa_id', '=', 'sk_siswa_id')
                        ->leftJoin('arsip', function($join){
                            $join->on('arsip_materi_id', '=', 'materi_id');
                            $join->on('arsip_siswa_id', '=', 'siswa_id');
                        })
                        ->where('siswa_id', '=', $request->idSiswa)
                        ->whereRaw("siswa_kelas.created_at = (select 
                            max(created_at) from siswa_kelas
                            where sk_siswa_id = $request->idSiswa)")
                        ->get();

            foreach ($materi as $key) {
                $key->materi_is_arsip = true;
                if (is_null($key->arsip_id)) {
                    $key->materi_is_arsip = false;
                }

                unset($key->arsip_id);
            }

            return [
                'success'   => true,
                'info'      => 'Success get data from DB',
                'data'      => ['materi' => $materi]
            ];      
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function getUserProfile(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            $data['siswa'] = SiswaKelas::select('siswa_nama_lengkap', 'siswa_alamat', 'siswa_dob', 
                            'siswa_telepon', 'siswa_foto', 'siswa_username', DB::Raw('kelas_id as siswa_kelas_id'),DB::Raw("CONCAT(kelas_tingkat, ' ', kelas_nama) as siswa_kelas_kelas"), DB::Raw('kelas_tahun_ajaran as siswa_kelas_ta'))
                                ->join(DB::Raw("(select max(created_at) as ca from siswa_kelas GROUP BY sk_siswa_id) as ca"), 'ca.ca', '=', DB::Raw('siswa_kelas.created_at'))
                                ->rightJoin('siswa', 'siswa_id', '=', 'sk_siswa_id')
                                ->leftJoin('kelas', 'sk_kelas_id', '=', 'kelas_id')
                                ->where('siswa_id', '=', $request->idSiswa)->get();

            return [
                'success'   => true,
                'info'      => 'Success get data from DB',
                'data'      => $data
            ];      
        } catch (Exception $e) {
            return [
                'success'   => false,
                'info'      => $e->getMessage(),
                'data'      => null
            ];  
        }
    }

    public function getWaktuServer(){
        return [
            'success'   => true,
            'info'      => 'Success get server time',
            'data'      => date('Y-m-d H:i:s')
        ]; 
    }

    public function getNotifikasi(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            $siswa = Siswa::join('siswa_kelas', 'sk_siswa_id', '=', 'siswa_id')
                    ->join('kelas', 'kelas_id', '=', 'sk_kelas_id')
                    ->where('sk_siswa_id', $request->idSiswa)
                    ->whereRaw("siswa_kelas.created_at = (select 
                                max(created_at) from siswa_kelas
                                where sk_siswa_id = $request->idSiswa)")
                    ->first();

            $idKelas        = '';
            $tingkatKelas   = '';

            if (!empty($siswa)) {
                $idKelas        = $siswa->kelas_id;
                $tingkatKelas   = $siswa->kelas_tingkat;
            }

            $notif = Notifikasi::select('notif_id', 'notif_jenis', 'notif_title', 'notif_konten', 
                        DB::Raw('COALESCE(notif_open.created_at, 0) as status_buka'))
                        ->leftJoin('notif_to', 'notif_id', '=', 'nt_notif_id')
                        ->leftJoin('notif_open', 'notif_id', '=', 'no_notif_id')
                        ->where("notif_jenis", 'VIDEO')
                        ->orWhereRaw("(nt_key = 'kelas_id' AND nt_value = '$idKelas')")
                        ->orWhereRaw("(nt_key = 'kelas_tingkat' AND nt_value = '$tingkatKelas')")
                        ->orWhereRaw("(nt_key = 'siswa_id' AND nt_value = '$request->idSiswa')")
                        ->groupBy('notif_id')
                        ->orderBy('notif.created_at', 'DESC')->get();

            $data = array(); 
            $data['new_notif'] = 0;
            foreach ($notif as $n) {
                if ($n->status_buka == 0) {
                    $n->notif_buka = 0;
                    $data['new_notif']++;
                }else{
                    $n->notif_buka = 1;
                }

                unset($n->status_buka);

                $data['notifikasi'][] = $n;
            }

            return [
                'success'   => true,
                'info'      => 'Success get data from DB',
                'data'      => $data
            ]; 
        } catch (Exception $e) {
            return [
                'success'   => true,
                'info'      => 'Failed get data from DB',
                'data'      => $e->getMessage()
            ]; 
        }
        
    }

    public function openNotifikasi(Request $request){
        $validate = Validator::make($request->all(), [
            'idSiswa'       => 'required|numeric',
            'idNotifikasi'  => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return [
                'success'   => false,
                'info'      => $validate->errors(),
                'data'      => null
            ];
        }

        try {
            $is_exist = NotifikasiOpen::where('no_siswa_id', $request->idSiswa)
                ->where('no_notif_id', $request->idNotifikasi)->first();

            if ($is_exist) {
                return [
                    'success'   => true,
                    'info'      => 'Data already exist in DB',
                    'data'      => null
                ];
            }else{
                NotifikasiOpen::insert([
                    'no_siswa_id'  => $request->idSiswa,
                    'no_notif_id'  => $request->idNotifikasi
                ]);

                return [
                    'success'   => true,
                    'info'      => 'Success insert data to DB',
                    'data'      => null
                ];
            }

        } catch (Exception $e) {
            return [
                'success'   => true,
                'info'      => 'Failed insert data to DB',
                'data'      => $e->getMessage()
            ];
        }
    }

}
