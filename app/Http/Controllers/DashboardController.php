<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SuratCinta;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function userIndex()
    {
        $user = Auth::user();
        if ($user->is_admin) {
            return redirect('/admin');
        }

        $surats = SuratCinta::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('dashboard.user', compact('user', 'surats', 'transactions'));
    }

    public function adminIndex()
    {
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect('/dashboard');
        }

        $pendingTransactions = Transaction::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $recentSurats = SuratCinta::orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard.admin', compact('user', 'pendingTransactions', 'recentSurats'));
    }

    public function showTopupForm()
    {
        return view('dashboard.topup');
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'paket' => 'required|in:1,5,15,35'
        ]);

        $harga = [
            '1' => 1000,
            '5' => 5000,
            '15' => 10000,
            '35' => 20000,
        ];

        $jumlahKredit = $request->paket;
        $totalHarga = $harga[$jumlahKredit];

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'kode_invoice' => 'INV-' . strtoupper(Str::random(8)),
            'jumlah_kredit' => $jumlahKredit,
            'total_harga' => $totalHarga,
            'status' => 'pending'
        ]);

        $pesanWa = "Halo Admin BucininAja, saya ingin konfirmasi pembayaran Top-Up Kredit.%0A%0A";
        $pesanWa .= "Invoice: *" . $transaction->kode_invoice . "*%0A";
        $pesanWa .= "Email: " . Auth::user()->email . "%0A";
        $pesanWa .= "Total Pembayaran: *Rp " . number_format($totalHarga, 0, ',', '.') . "*%0A%0A";
        $pesanWa .= "Saya akan mengirimkan bukti transfer setelah ini. Terima kasih.";

        return redirect("https://wa.me/6285155238654?text=" . $pesanWa);
    }

    public function approvePayment($id)
    {
        if (!Auth::user()->is_admin) {
            return abort(403);
        }

        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi sudah diproses.');
        }

        $transaction->update(['status' => 'approved']);
        
        $user = $transaction->user;
        $user->credits += $transaction->jumlah_kredit;
        $user->save();

        return back()->with('success', 'Pembayaran berhasil disetujui. Kredit telah ditambahkan.');
    }
}
