<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_session',
        'message',
    ];

    public function room()
    {
        return $this->belongsTo(AnonChatRoom::class, 'chat_room_id');
    }
}
