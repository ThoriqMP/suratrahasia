<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnonRoom extends Model
{
    use HasFactory;

    
    protected $fillable = ['kode', 'kode_form'];


    public function messages()
    {
        return $this->hasMany(AnonMessage::class);
    }
    public function room()
    {
        return $this->belongsTo(\App\Models\AnonRoom::class);
    }

}
