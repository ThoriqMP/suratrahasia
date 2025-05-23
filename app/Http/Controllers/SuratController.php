<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\SuratCinta;

class SuratController extends Controller
{
    public function create() {
        return view('form');
    }

    public function store(Request $request) {
        $request->validate([
            'dari' => 'required|string|max:255',
            'untuk' => 'required|string|max:255',
            'isi' => 'required|string',
            'password' => 'required|string|min:3',
            'waktu_hapus' => 'nullable|integer|min:1|max:30'
        ]);

        $kode = Str::random(8);
        $surat = new SuratCinta();
        $surat->kode = $kode;
        $surat->dari = $request->dari;
        $surat->untuk = $request->untuk;
        $surat->isi = $request->isi;
        $surat->password = bcrypt($request->password);
        $surat->waktu_hapus = $request->waktu_hapus ?? null;
        $surat->save();

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

                // Jika user tidak menentukan waktu hapus, pakai default 7 hari dari dibuka
                if (is_null($surat->waktu_hapus)) {
                    $surat->waktu_hapus = 7;
                }

                $surat->save();
            }

            return view('lihat', compact('surat'));
        }

        return back()->with('error', 'Password salah!');
    }
}
