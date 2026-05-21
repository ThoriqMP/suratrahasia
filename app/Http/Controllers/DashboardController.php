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
        $packages = CreditPackage::orderBy('jumlah_kredit')->get();
        
        // Active users directory with letter count
        $users = User::withCount('surats')->orderBy('created_at', 'desc')->get();

        // System usage reports
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_surat' => SuratCinta::count(),
            'total_dibuka' => SuratCinta::whereNotNull('dibuka_pada')->count(),
            'total_transaksi' => Transaction::count(),
            'total_pendapatan' => Transaction::where('status', 'approved')->sum('total_harga'),
            'total_kredit' => User::where('is_admin', false)->sum('credits') ?: 0,
        ];

        return view('dashboard.admin', compact('user', 'pendingTransactions', 'packages', 'users', 'stats'));
    }

    public function showTopupForm()
    {
        if (Auth::user()->is_admin) {
            return redirect('/admin');
        }
        $packages = CreditPackage::orderBy('jumlah_kredit')->get();
        return view('dashboard.topup', compact('packages'));
    }

    public function processTopup(Request $request)
    {
        if (Auth::user()->is_admin) {
            return redirect('/admin');
        }
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

    public function updateUserCredits(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            return abort(403);
        }

        $request->validate([
            'credits' => 'required|integer|min:0'
        ]);

        $targetUser = User::findOrFail($id);
        $oldCredits = $targetUser->credits;
        $targetUser->credits = $request->credits;
        $targetUser->save();

        return back()->with('success', "Kredit untuk user {$targetUser->name} berhasil diperbarui dari {$oldCredits} menjadi {$request->credits}.");
    }
}
