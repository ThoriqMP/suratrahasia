<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AnonRoom;
class AnonMessageController extends Controller
{
    public function showForm($kode_form)
    {
        $room = AnonRoom::where('kode_form', $kode_form)->firstOrFail();

        return view('anon.send', compact('room'));
    }

    public function store(Request $request, $kode_form)
    {
        $room = AnonRoom::where('kode_form', $kode_form)->firstOrFail();

        $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        $room->messages()->create([
            'isi' => $request->isi,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
    public function showMessages($kode)
    {
        $room = AnonRoom::where('kode', $kode)->firstOrFail();
        $messages = $room->messages()->latest()->get();

        return view('anon.room', compact('room', 'messages'));
    }



}
