<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnonMessage extends Model
{
    use HasFactory;

    protected $fillable = ['anon_room_id', 'isi'];

    public function room()
    {
        return $this->belongsTo(AnonRoom::class);
    }
    public function messages()
    {
        return $this->hasMany(\App\Models\AnonMessage::class);
    }

}
