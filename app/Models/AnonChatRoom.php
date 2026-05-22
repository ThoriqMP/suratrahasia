<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_token',
        'user1_session',
        'user1_gender',
        'user2_session',
        'user2_gender',
        'status',
    ];

    public function messages()
    {
        return $this->hasMany(AnonChatMessage::class, 'chat_room_id');
    }
}
