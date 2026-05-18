<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bucininaja.com'],
            [
                'name' => 'Admin BucininAja',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'credits' => 999999,
                'no_wa' => '085155238654'
            ]
        );
    }
}
