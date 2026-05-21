<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratCinta;
use App\Models\CreditPackage;

class SuratController extends Controller
{
    public function create() {
        return view('form');
    }
    public function index() {
        $packages = CreditPackage::orderBy('jumlah_kredit')->get();
        return view('beranda', compact('packages'));
    }
    
    public function store(Request $request) {
        $user = Auth::user();
        if ($user->credits < 1) {
            return redirect()->route('topup.form')->withErrors(['credits' => 'Kredit kamu tidak cukup. Silakan top-up terlebih dahulu.']);
        }

        $request->validate([
            'dari' => 'required|string|max:255',
            'untuk' => 'required|string|max:255',
            'isi' => 'required|string',
            'password' => 'required|string|min:3',
            'tema_desain' => 'required|in:classic,neon,vintage'
        ]);

        $kode = Str::random(8);
        $surat = new SuratCinta();
        $surat->kode = $kode;
        $surat->user_id = $user->id;
        $surat->dari = $request->dari;
        $surat->untuk = $request->untuk;
        $surat->isi = $request->isi;
        $surat->password = bcrypt($request->password);
        $surat->waktu_hapus = null;
        $surat->tema_desain = $request->tema_desain;
        $surat->save();

        $user->credits -= 1;
        $user->save();

        // Tampilkan view dengan link
        return view('surat.success', ['kode' => $kode]);
    }

    public function show($kode) {
        $surat = SuratCinta::where('kode', $kode)->firstOrFail();
        return view('buka', compact('surat'));
    }

    public function unlock(Request $request, $kode) {
        $request->validate([
            'password' => 'required|string'
        ]);

        $surat = SuratCinta::where('kode', $kode)->firstOrFail();

        if (Hash::check($request->password, $surat->password)) {
            if (is_null($surat->dibuka_pada)) {
                $surat->dibuka_pada = now();
                $surat->save();
            }

            return view('lihat', compact('surat'));
        }

        return back()->with('error', 'Password salah!');
    }
    public function showStatistikForm() {
        return view('statistik_login');
    }

    public function statistik(Request $request) {
        $request->validate([
            'password' => 'required|string'
        ]);

        if ($request->password !== '010304Tmp') {
            return back()->with('error', 'Password salah!');
        }

        $jumlahSurat = SuratCinta::count();
        $jumlahDibuka = SuratCinta::whereNotNull('dibuka_pada')->count();

        return view('statistik', compact('jumlahSurat', 'jumlahDibuka'));
    }
   public function hasilPencarian(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
        ]);

        $input = trim($request->kode);

        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $pathSegments = explode('/', parse_url($input, PHP_URL_PATH));
            $kode = end($pathSegments);
        } else {
            $kode = $input;
        }

        $surat = SuratCinta::where('kode', $kode)->first();

        return view('statistik-cari', [
            'surat' => $surat,
            'kode' => $kode,
            'input' => $input,
        ]);
    }

    public function updatePassword(Request $request, $id) {
        $request->validate([
            'password' => 'required|string|min:3'
        ]);

        $surat = SuratCinta::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $surat->password = bcrypt($request->password);
        $surat->save();

        return back()->with('success', 'Password surat berhasil diperbarui!');
    }
}
