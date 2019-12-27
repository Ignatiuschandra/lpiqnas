<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SiswaPembayaran;
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
use Validator;
use DB;

class C_api extends Controller
{
	public function index(){
		echo "LPIQNAS API is ONLINE!";
	}

	public function register(Request $request){
    	$validate = Validator::make($request->all(), [
    		'namaLengkap' 	=> 'required|string',
    		'noTelp' 		=> 'required|string',
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

    	$siswa = new Siswa();
    	$siswa->siswa_nama_lengkap 	= $request->namaLengkap;
    	$siswa->siswa_telepon 		= $request->noTelp;
    	$siswa->siswa_username 		= $request->username;
    	$siswa->siswa_password 		= password_hash($request->password, PASSWORD_DEFAULT);

    	if ($siswa->save()) {
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
    		'username' => 'required|string',
    		'password' => 'required|string'
    	]);

    	if ($validate->fails()) {
    		return [
    			'success'	=> false,
    			'info' 		=> $validate->errors(),
    			'data'		=> null
    		];
    	}

    	//cek username
    	$cek_user = Siswa::where('siswa_username', $request->username)->first();
    	if(empty($cek_user)){
    		return [
    			'success'	=> false,
    			'info' 		=> 'Username not found',
    			'data' 		=> null
    		];
    	}

    	//cek password
    	if(!password_verify($request->password, $cek_user->siswa_password)){
    		return [
    			'success'	=> false,
    			'info' 		=> 'Invalid password provided',
    			'data' 		=> false
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
    		'password'		=> 'required|string|same:password2',
    		'password2'		=> 'required|string',
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
    			'siswa_password' 		=> password_hash($request->password, PASSWORD_DEFAULT)
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

    public function getPengumuman(){
    	try {
    		$data['pengumuman'] = Pengumuman::select('pengumuman_id', 'pengumuman_judul', 'pengumuman_konten', DB::raw('admin_nama_lengkap as pengumuman_pembuat'), DB::raw('DATE(pengumuman.created_at) as pengumuman_tanggal'))
	    		->join('admin', 'pengumuman_pembuat_id', '=', 'admin.admin_id')
	    		->where('pengumuman_expired', '>=', date('Y-m-d'))->get();

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
    		$data['materi'] = Materi::select('materi_nama', 'materi_detail')
	    		->where('materi_id', '=', $request->idMateri)->first();

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
	    			if ($jumlah->JUMLAH < $p->pembayaran_tagihan) {
	    				$data['pembayaran'][] = $p;
	    			}
	    		}else{
	    			// sudah pasti minta ditagih
	    			$data['pembayaran'][] = $p;
	    		}
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
    			->where('ujian_jadwal', 'LIKE', date('Y-m-d').'%')
	    		->where('sk_siswa_id', '=', $request->idSiswa)->get();

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
    			->where('ujian_id', $request->idUjian)->get();

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
    		$diskusiRaw = Diskusi::select('diskusi_id', 'diskusi_pertanyaan', DB::Raw('diskusi_jawaban_admin as diskusi_dijawab_admin'))->get();

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
    		$data['diskusi'] = Diskusi::select('diskusi_id', 'diskusi_pertanyaan', 'diskusi_jawaban_admin')->first();
			$data['diskusiKomentar'] = DiskusiKomentar::select('siswa_nama_lengkap', 'dk_komentar', 'diskusi_komentar.created_at')->join('siswa', 'dk_siswa_id', '=', 'siswa_id')->get();

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
	    		->where('sk_siswa_id', '=', $request->idSiswa)->get();

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
    		$data['videoBroadcasting'] = VideoBroadcasting::select('siswa_nama_lengkap', 'vb_judul', 'vb_link')
    										->join('siswa', 'vb_siswa_id', '=', 'siswa_id')->get();

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

}
