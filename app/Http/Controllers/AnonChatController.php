<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AnonChatRoom;
use App\Models\AnonChatMessage;
use App\Models\AnonChatQueue;

class AnonChatController extends Controller
{
    /**
     * Helper to get or create a stable unique anonymous session token for the user.
     */
    private function getAnonUserToken()
    {
        if (!session()->has('anon_chat_user_token')) {
            session(['anon_chat_user_token' => 'usr_' . Str::random(20)]);
        }
        return session('anon_chat_user_token');
    }

    /**
     * Halaman Utama Chat Anonim
     */
    public function index()
    {
        $token = $this->getAnonUserToken();
        
        // Find if this user is already in an active room
        $activeRoom = AnonChatRoom::where('status', 'active')
            ->where(function($query) use ($token) {
                $query->where('user1_session', $token)
                      ->orWhere('user2_session', $token);
            })->first();

        $activeRoomToken = $activeRoom ? $activeRoom->room_token : null;

        return view('anon.chat', compact('activeRoomToken'));
    }

    /**
     * Masuk ke Antrian Pencarian Partner
     */
    public function joinQueue(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:Laki-laki,Perempuan'
        ]);

        $token = $this->getAnonUserToken();
        $gender = $request->gender;

        // 1. Clean up old or stuck queue entries (older than 1 minute)
        AnonChatQueue::where('updated_at', '<', now()->subMinutes(1))->delete();

        // 2. End any stuck active rooms this user is in
        $existingRooms = AnonChatRoom::where('status', 'active')
            ->where(function($query) use ($token) {
                $query->where('user1_session', $token)
                      ->orWhere('user2_session', $token);
            })->get();
        foreach ($existingRooms as $room) {
            $room->status = 'ended';
            $room->save();
        }

        // 3. Search for opposite gender in the queue who is 'waiting'
        $oppositeGender = ($gender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';
        
        $match = AnonChatQueue::where('gender', $oppositeGender)
            ->where('status', 'waiting')
            ->where('session_id', '!=', $token)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($match) {
            // 4. Create new chat room
            $roomToken = 'room_' . Str::random(24);
            $room = AnonChatRoom::create([
                'room_token' => $roomToken,
                'user1_session' => $match->session_id,
                'user1_gender' => $match->gender,
                'user2_session' => $token,
                'user2_gender' => $gender,
                'status' => 'active'
            ]);

            // 5. Update the partner's queue entry
            $match->status = 'matched';
            $match->matched_room_id = $room->id;
            $match->save();

            // Delete current user from queue if exists (since matched immediately)
            AnonChatQueue::where('session_id', $token)->delete();

            return response()->json([
                'status' => 'matched',
                'room_token' => $roomToken
            ]);
        }

        // 6. No match found, join/update queue entry for current user
        AnonChatQueue::updateOrCreate(
            ['session_id' => $token],
            [
                'gender' => $gender,
                'status' => 'waiting',
                'matched_room_id' => null,
                'updated_at' => now()
            ]
        );

        return response()->json([
            'status' => 'waiting'
        ]);
    }

    /**
     * Cek status pencarian di antrian (Polling status)
     */
    public function checkQueueStatus()
    {
        $token = $this->getAnonUserToken();

        $queue = AnonChatQueue::where('session_id', $token)->first();

        if (!$queue) {
            // Check if they were already matched by someone else
            $activeRoom = AnonChatRoom::where('status', 'active')
                ->where(function($query) use ($token) {
                    $query->where('user1_session', $token)
                          ->orWhere('user2_session', $token);
                })->first();

            if ($activeRoom) {
                return response()->json([
                    'status' => 'matched',
                    'room_token' => $activeRoom->room_token
                ]);
            }

            return response()->json([
                'status' => 'idle'
            ]);
        }

        if ($queue->status === 'matched' && $queue->matched_room_id) {
            $room = AnonChatRoom::find($queue->matched_room_id);
            if ($room) {
                // Delete matched queue entry
                $queue->delete();

                return response()->json([
                    'status' => 'matched',
                    'room_token' => $room->room_token
                ]);
            }
        }

        // Keep session alive in queue by updating timestamp
        $queue->touch();

        return response()->json([
            'status' => 'waiting'
        ]);
    }

    /**
     * Batal mencari partner
     */
    public function leaveQueue()
    {
        $token = $this->getAnonUserToken();
        AnonChatQueue::where('session_id', $token)->delete();

        return response()->json([
            'status' => 'idle'
        ]);
    }

    /**
     * Polling pesan baru & status room
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'room_token' => 'required|string'
        ]);

        $token = $this->getAnonUserToken();
        $room = AnonChatRoom::where('room_token', $request->room_token)->first();

        if (!$room) {
            return response()->json([
                'status' => 'not_found'
            ], 404);
        }

        // Get the list of messages in this room
        $messages = $room->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($token) {
                return [
                    'id' => $msg->id,
                    'is_mine' => $msg->sender_session === $token,
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('H:i')
                ];
            });

        // Determine if partner is still active
        $partnerStatus = ($room->status === 'active') ? 'active' : 'ended';

        return response()->json([
            'room_status' => $room->status,
            'partner_status' => $partnerStatus,
            'messages' => $messages
        ]);
    }

    /**
     * Kirim Pesan
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'room_token' => 'required|string',
            'message' => 'required|string|max:1000'
        ]);

        $token = $this->getAnonUserToken();
        $room = AnonChatRoom::where('room_token', $request->room_token)
            ->where('status', 'active')
            ->first();

        if (!$room) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan tidak aktif.'
            ], 400);
        }

        // Check if sender is part of this room
        if ($room->user1_session !== $token && $room->user2_session !== $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $message = AnonChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_session' => $token,
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'is_mine' => true,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i')
            ]
        ]);
    }

    /**
     * Akhiri Obrolan secara sukarela
     */
    public function endChat(Request $request)
    {
        $request->validate([
            'room_token' => 'required|string'
        ]);

        $token = $this->getAnonUserToken();
        $room = AnonChatRoom::where('room_token', $request->room_token)->first();

        if ($room) {
            $room->status = 'ended';
            $room->save();
        }

        // Clean up queue entry if stuck
        AnonChatQueue::where('session_id', $token)->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
