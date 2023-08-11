<?php

namespace App\Http\Controllers;

use App\Models\KonselingBK;
use App\Models\SiswaKonseling;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogActivity;
use App\Models\LayananBK;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\WaliKelas;


class AuthController extends Controller
{
    function login(Request $R){
        $user= User::where('email', $R->email)->first();

        if($user!='[]' && Hash::check($R->password,$user->password)){
            $token = $user->createToken('secret')->plainTextToken;
            $response= ['status'=>200,'token'=>$token,'user'=>$user,'message'=>'Successfully Login'];
            return response()->json($response); 
        }else if($user=='[]'){
            $response= ['status'=>500,'user'=>$user,'message'=>'Not Found Account found with this Email'];
            return response()->json($response); 
        }else{
            $response= ['status'=>500,'user'=>$user,'message'=>'Wrong Email Or Password, Try Again!'];
            return response()->json($response); 
        }
    }
    

    public function history($id){
        $user = User::find($id);
        $siswa = $user->siswa;
        $konselingBk = $siswa->konseling;
        $array = [];

        foreach ($konselingBk as $item) {
            array_push($array, [
                "nama_bk" => $item->guru->nama,
                'nama_layanan' => $item->layanan->jenis_layanan,
                'jam_mulai' => $item->jam_mulai,
                // "wali_kelas" => $item->konseling->walas->nama,
                // "jenis_layanan" => $item->konseling->layanan->jenis_layanan,
                // "siswa_konseling" => $item->siswa->nama,
                // "tanggal" => $item->konseling->tanggal_konseling,
                // "jam_mulai" => $item->konseling->jam_mulai,
                // "tempat" => $item->konseling->tempat,
                // "pesan" => $item->konseling->pesan,
                // "hasil" => $item->konseling->hasil_konseling,
                // "status" => $item->konseling->status,
            ]);
        }
        return response()->json(
            $array,
        );
    }

    public function history2() {
        $history = KonselingBK::all();
        $siswaKonseling = SiswaKonseling::all();
        return response()->json([
            'history' => $history,
        ]);
    }
    // create

    public function create()
    {
        $layananBK = LayananBK::all();
        $siswaData = Siswa::all();
    
        // Assuming you want to return all the data as a JSON response
        return response()->json([
            'layananBK' => $layananBK,
            'siswa' => $siswaData,
        ],200);
    }

    public function store(Request $request){
        $request->validate([
            'id_layanan' => 'required',
            'tanggal_konseling' => 'required',
            'jam_mulai' => 'required',
            'tempat' => 'required',
            'pesan' => 'required',
        ]); 

        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return response()->json(['message' => 'Anda bukan seorang siswa'], 403);
        }

        $kelasIdSiswa = $siswa->kelas_id;
        $kelas = Kelas::find($kelasIdSiswa);
        if (!$kelas) {
            return response()->json(['message' => 'Tidak ditemukan kelas siswa'], 404);
        }

        $bk = Guru::find($kelas->guru_id);
        $walas = WaliKelas::find($kelas->wali_kelas_id);


        $konseling = KonselingBK::create([
            'id_layanan' => $request->id_layanan,
            'id_bk' => $bk->id,
            'id_walas' => $walas->id,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_mulai' => $request->jam_mulai,
            'tempat' => $request->tempat,
            'pesan' => $request->pesan,
            'status' => 'Waiting',
        ]);

        $temanArray = json_decode($request->input('teman'), true);

        $siswaKonselingData = [];

        if (!empty($temanArray) && is_array($temanArray)) {
            foreach ($temanArray as $item) {
                $siswaKonselingData[] = [
                    'id_siswa' => $item,
                    'id_konseling' => $konseling->id,
                ];
            }
        }

        // Menambahkan data konseling untuk pengguna yang diautentikasi (siswa)
        $siswaKonselingData[] = [
            'id_siswa' => $siswa->id,
            'id_konseling' => $konseling->id,
        ];

        // Menyimpan data siswa_konseling ke dalam tabel
        SiswaKonseling::insert($siswaKonselingData);

        $response = [
            'message' => 'Konseling data created successfully',
            'konseling' => $konseling,
            'siswa_konseling' => $siswaKonselingData,
        ];

        return response()->json($response, 201);
        
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => "Logout success"
        ], 200);
    }

    public function user(){
        return response([
            'user'=>auth()->user()
        ],200);
    }
}