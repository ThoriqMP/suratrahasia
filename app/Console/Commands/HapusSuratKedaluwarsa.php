<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratCinta;
use Carbon\Carbon;

class HapusSuratKedaluwarsa extends Command
{
    protected $signature = 'surat:hapus-kedaluwarsa';
    protected $description = 'Menghapus surat cinta yang kedaluwarsa otomatis';

    public function handle()
    {
        $now = Carbon::now();

        // Hapus surat yang SUDAH DIBUKA dan melebihi waktu hapus
        $hapusDibuka = SuratCinta::whereNotNull('dibuka_pada')
            ->whereRaw("DATE_ADD(dibuka_pada, INTERVAL waktu_hapus DAY) <= ?", [$now])
            ->delete();

        // Hapus surat yang BELUM DIBUKA dan lebih dari 3 hari tidak dibuka
        $hapusBelumDibuka = SuratCinta::whereNull('dibuka_pada')
            ->where('created_at', '<=', $now->subDays(3))
            ->delete();

        $this->info("Surat dibuka dihapus: $hapusDibuka");
        $this->info("Surat tidak dibuka dihapus: $hapusBelumDibuka");
    }
}
