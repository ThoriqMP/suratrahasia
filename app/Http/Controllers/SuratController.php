<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\SuratCinta;

class SuratController extends Controller
{
    // Form untuk menulis surat
    public function create() {
        return view('form');
    }

    // Simpan surat ke database
    public function store(Request $request) {
        $request->validate([
            'dari' => 'required|string|max:255',
            'untuk' => 'required|string|max:255',
            'isi' => 'required|string',
            'password' => 'required|string|min:3'
        ]);

        $kode = Str::random(8);

        SuratCinta::create([
            'kode' => $kode,
            'dari' => $request->dari,
            'untuk' => $request->untuk,
            'isi' => $request->isi,
            'password' => bcrypt($request->password)
        ]);

        return redirect("/surat/{$kode}")->with('success', 'Surat berhasil dibuat!');
    }

    // Tampilkan form untuk buka surat (dengan password)
    public function show($kode) {
        $surat = SuratCinta::where('kode', $kode)->firstOrFail();
        return view('buka', compact('surat'));
    }

    // Validasi password & tampilkan isi surat
    public function unlock(Request $request, $kode) {
        $request->validate([
            'password' => 'required|string'
        ]);

        $surat = SuratCinta::where('kode', $kode)->firstOrFail();

        if (Hash::check($request->password, $surat->password)) {
            return view('lihat', compact('surat'));
        }

        return back()->with('error', 'Password salah!');
    }
}
