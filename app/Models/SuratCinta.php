<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratCinta extends Model
{
    protected $table = 'surat_cinta';

    protected $fillable = [
        'kode', 'dari', 'untuk', 'isi', 'password'
    ];
}
