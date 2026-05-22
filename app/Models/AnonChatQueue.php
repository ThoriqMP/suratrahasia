<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonChatQueue extends Model
{
    use HasFactory;

    protected $table = 'anon_chat_queue';

    protected $fillable = [
        'session_id',
        'gender',
        'status',
        'matched_room_id',
    ];

    public function matchedRoom()
    {
        return $this->belongsTo(AnonChatRoom::class, 'matched_room_id');
    }
}
