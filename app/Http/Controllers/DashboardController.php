<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SuratCinta;
use App\Models\Transaction;
use App\Models\User;
use App\Models\CreditPackage;

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
        $packages = CreditPackage::orderBy('jumlah_kredit')->get();

        return view('dashboard.admin', compact('user', 'pendingTransactions', 'recentSurats', 'packages'));
    }

    public function showTopupForm()
    {
        $packages = CreditPackage::orderBy('jumlah_kredit')->get();
        return view('dashboard.topup', compact('packages'));
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'paket' => 'required|exists:credit_packages,id'
        ]);

        $package = CreditPackage::findOrFail($request->paket);
        $jumlahKredit = $package->jumlah_kredit;
        $totalHarga = $package->harga;

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

    public function updatePackage(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            return abort(403);
        }

        $package = CreditPackage::findOrFail($id);
        $package->update([
            'nama_paket' => $request->nama_paket,
            'jumlah_kredit' => $request->jumlah_kredit,
            'harga' => $request->harga,
            'is_popular' => $request->has('is_popular')
        ]);

        return back()->with('success', 'Paket kredit berhasil diperbarui.');
    }
}
