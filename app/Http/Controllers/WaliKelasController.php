<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\WaliKelas;
use App\Models\Siswa;
use App\Models\JenisKerawanan;
use App\Models\KonselingBK;


use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index_walas(){
        return redirect('/walas/kerawanan');
    }

    public function index_layanan(){
        $user = Auth::user();
        $bk = $user->wali_kelas;
        $konselingFinished = KonselingBK::where('id_walas', $bk->id)->where('status', 'Finished')->get();
        return view('dashboard.page.data-konsul-walas', compact('konselingFinished'));
    }
}
