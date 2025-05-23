<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratCinta extends Model
{
    protected $table = 'surat_cinta'; // <- tambahkan ini!
    protected $fillable = [
    'kode', 'dari', 'untuk', 'isi', 'password', 'waktu_hapus', 'dibuka_pada'
    ];

    protected $dates = ['dibuka_pada'];

}
