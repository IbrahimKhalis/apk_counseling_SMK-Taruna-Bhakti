<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KonselingBK;
use App\Models\LayananBK;
use App\Models\Siswa;
use App\Models\SiswaKonseling;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Countable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LogActivity;
use App\Models\Quote;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layananBK = LayananBK::all();
        $user = Auth::user();
        $profile = $user->siswa;
        $siswa = Siswa::all();

        $userKonsul = Auth::user()->siswa;
        $dataKonseling = SiswaKonseling::where('id_siswa', $userKonsul->id)->pluck('id_konseling')->toArray();
        $konselingBK = KonselingBK::find($dataKonseling);
        $quotes = Quote::all();
        $data = [];

        foreach ($konselingBK as $konseling) {
            $tanggal = $konseling->tanggal_konseling;
            $tanggalArray = explode('-', $tanggal);
            $tahun = $tanggalArray[0];
            $bulan = $tanggalArray[1];
            $hari = $tanggalArray[2];

            $data[] = [
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'layanan' => $konseling->layanan->jenis_layanan,
                // Tambahkan data lain yang diperlukan
            ];
        }

        return view('profile', compact('layananBK', 'siswa', 'profile','konselingBK','tahun','bulan','hari','quotes'));
    }

    public function indexGuru() {
        $layananBK = LayananBK::all();
        $user = Auth::user();
        $profile = $user->siswa;
        $siswa = Siswa::all();
        // $dataKelas = Kelas::where('guru_id', Auth::user()->id);
        // dd($dataKelas);
        // $dataWalas = $dataKelas->walas_id; 


        return view('dashboard.page.form-konsul-guru', compact('layananBK','profile','siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }
    /**
     * Store a newly created resource in storage.
     */ 
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required',
            'id_bk' => 'required',
            'id_walas' => 'required',
            'tanggal_konseling' => 'required',
            'jam_mulai' => 'required',
            'tempat' => 'required',
            'pesan' => 'required',
        ]);
            $konseling = KonselingBK::create([
                'id_layanan' => $request->id_layanan,
                'id_bk' => $request->id_bk,
                'id_walas' => $request->id_walas,
                'tanggal_konseling' => $request->tanggal_konseling,
                'jam_mulai' => $request->jam_mulai,
                'tempat' => $request->tempat,
                'pesan' => $request->pesan,
                'status' => 'Waiting'
            ]);


        if($request->teman != [] && sizeof($request->teman) >= 1 ) {
            foreach($request->teman as $item) {
                SiswaKonseling::insert([
                    'id_siswa' => $item,
                    'id_konseling' => $konseling->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            SiswaKonseling::insert([
                'id_siswa' => Auth::user()->siswa->id,
                'id_konseling' => $konseling->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }else{
            SiswaKonseling::insert([
                'id_siswa' => Auth::user()->siswa->id,
                'id_konseling' => $konseling->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        LogActivity::create([
            'Activity' => auth()->user()->name. ' telah membuat jadwal konseling  '
        ]);

        if(Auth::user()->hasRole('user')) {
            return redirect('/');
        }else{
            return redirect('/layanan');
        };

        
    }

    public function storeFromGuru(Request $request) {
    $validate = $request->validate([
        'siswa' => 'required'
    ]);

        $user = Auth::user();
        $dataGuru = Guru::where('user_id', $user->id)->first();
        $dataKelas = Kelas::where('guru_id', $dataGuru->id)->first();
        $dataWalas = WaliKelas::where('id', $dataKelas->wali_kelas_id)->first();
        // dd($dataKelas);

        $request->validate([
            'id_layanan' => 'required',
            'tanggal_konseling' => 'required',
            'jam_mulai' => 'required',
            'tempat' => 'required',
            'pesan' => 'required',
        ]);

        $konseling = KonselingBK::create([
            'id_layanan' => $request->id_layanan,
            'id_bk' => $dataGuru->id,
            'id_walas' => $dataWalas->id,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_mulai' => $request->jam_mulai,
            'tempat' => $request->tempat,
            'pesan' => $request->pesan,
            'status' => 'Approved'
        ]);


        foreach ($validate['siswa'] as $item) {
            SiswaKonseling::create([
                'id_siswa' => $item,
                'id_konseling' => $konseling->id
            ]);
        }

        // if($request->teman != [] && sizeof($request->teman) >= 1 ) {
        //     foreach($request->teman as $item) {
        //         SiswaKonseling::insert([
        //             'id_siswa' => $item,
        //             'id_konseling' => $konseling->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }
        //     SiswaKonseling::insert([
        //         'id_siswa' => Auth::user()->siswa->id,
        //         'id_konseling' => $konseling->id,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }else{
        //     SiswaKonseling::insert([
        //         'id_siswa' => Auth::user()->siswa->id,
        //         'id_konseling' => $konseling->id,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }

        return redirect('/guru/layanan/data');
    }
    public function indexRequest() {
        $user = Auth::user();
        $bk = $user->guru;
        $konseling = KonselingBK::where('id_bk', $bk->id)->where('status', 'Waiting')->get();
        
        // $layananBK = LayananBK::all();
        // $guruBK = Guru::where('user_id', Auth()->id())->first();
        // $walas = Kelas::where('guru_id', $guruBK->id)->first();

        // $konseling =KonselingBK::where('id_bk', $walas->guru_id)->where('id_walas', $walas->id_walas)->where('id_layanan', 4)->where('status', 'Waiting')->get();

        return view('dashboard.page.konsul-request', compact('konseling'));
    }
    public function approve($id) {
        $konseling = KonselingBK::findOrFail($id);
        $konseling->update([
            'status' => 'Approve'
        ]);
        LogActivity::create([
            'Activity' => auth()->user()->name. ' telah mengaprove   '
        ]);
        return redirect('/guru/layanan/request')->with(['success' => 'Data sudah di approve']);
    }
    public function reschedule(Request $request,$id) {
        $konseling = KonselingBK::findOrFail($id);
        $konseling->update([
            'status' => 'Reschedule',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_mulai' => $request->jam_konseling,
            'tempat' => $request->tempat_konseling,
        ]);
        LogActivity::create([
            'Activity' => auth()->user()->name. ' telah mengatur ulang jadwal   '
        ]);
        return redirect('/guru/layanan/request')->with(['success' => 'Data sudah di reschedule']);
    }
    public function dataConfirm() {
        $user = Auth::user();
        $bk = $user->guru;
        $konselingConfirm = KonselingBK::where('id_bk', $bk->id)->where('status', 'Approve')->orWhere('status', 'Reschedule')->get();
        return view('dashboard.page.konsul-confirm', compact('konselingConfirm'));
    }
    public function confirmStore(Request $request,$id) {
        $konseling = KonselingBK::findOrFail($id);
        $konseling->update([
            'status' => 'Finished',
            'hasil_konseling' => $request->hasil_konseling
        ]);
        LogActivity::create([
            'Activity' => auth()->user()->name. ' telah membuat hasil konseling   '
        ]);
        return redirect('/guru/layanan/data')->with(['success' => 'Data konseling sudah selesai']);
    }
    public function konselingFinished() {
        $user = Auth::user();
        $bk = $user->guru;
        $konselingFinished = KonselingBK::where('id_bk', $bk->id)->where('status', 'Finished')->get();
        return view('dashboard.page.konsul-finished', compact('konselingFinished'));
    }


    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
