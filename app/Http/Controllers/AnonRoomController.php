<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AnonRoom;

class AnonRoomController extends Controller
{
    /**
     * Menampilkan halaman untuk membuat room baru.
     */
    public function createRoom()
    {
        return view('anon.create-room');
    }

    /**
     * Menyimpan room baru dan mengarahkan ke halaman room.
     */
    public function storeRoom()
    {
        // Kode untuk pemilik melihat pesan
        $kode = Str::random(8);
        // Kode form untuk publik mengirim pesan
        $kode_form = Str::random(8);

        // Simpan ke database
        $room = AnonRoom::create([
            'kode' => $kode,
            'kode_form' => $kode_form,
        ]);

        // Simpan ke browser untuk proteksi akses (opsional)
        session(['my_room_code' => $kode]);

        // Redirect ke halaman room
        return redirect()->route('anon.show', $kode);
    }
}
