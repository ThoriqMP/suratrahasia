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
        $this->info("Sistem hapus otomatis dinonaktifkan. Surat disimpan selamanya.");
        return 0;
    }
}
