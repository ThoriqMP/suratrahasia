<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreditPackage;

class CreditPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'nama_paket' => '1 Kredit',
                'jumlah_kredit' => 1,
                'harga' => 1000,
                'is_popular' => false,
            ],
            [
                'nama_paket' => '5 Kredit',
                'jumlah_kredit' => 5,
                'harga' => 5000,
                'is_popular' => false,
            ],
            [
                'nama_paket' => '15 Kredit',
                'jumlah_kredit' => 15,
                'harga' => 10000,
                'is_popular' => true,
            ],
            [
                'nama_paket' => '35 Kredit',
                'jumlah_kredit' => 35,
                'harga' => 20000,
                'is_popular' => false,
            ],
        ];

        foreach ($packages as $pkg) {
            CreditPackage::updateOrCreate(
                ['jumlah_kredit' => $pkg['jumlah_kredit']],
                $pkg
            );
        }
    }
}
