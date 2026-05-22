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

        // Check if user has been waiting in the queue for more than 60 seconds (1 minute)
        // If so, fallback and match them with a dynamic chatbot partner!
        if ($queue->created_at->lt(now()->subSeconds(60))) {
            $userGender = $queue->gender;
            $queue->delete();

            // Determine bot gender and pick a realistic random name
            $botGender = ($userGender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';
            $botNames = ($userGender === 'Laki-laki')
                ? ['Sarah', 'Dinda', 'Adinda', 'Amanda', 'Ayu', 'Keyla', 'Tasya', 'Nabila', 'Indah', 'Amelia', 'Clarissa', 'Clara', 'Nadine', 'Aulia', 'Jessica', 'Rachel']
                : ['Rian', 'Aditya', 'Fatur', 'Bintang', 'Raka', 'Dimas', 'Bima', 'Aris', 'Kevin', 'Farel', 'Daniel', 'Gilang', 'Rendy', 'Rehan', 'Fikri', 'Taufik'];
            
            $chosenName = $botNames[array_rand($botNames)];
            $botSessionId = 'BOT_NAME_' . $chosenName;

            $roomToken = 'room_bot_' . Str::random(20);
            $room = AnonChatRoom::create([
                'room_token' => $roomToken,
                'user1_session' => $token,
                'user1_gender' => $userGender,
                'user2_session' => $botSessionId,
                'user2_gender' => $botGender,
                'status' => 'active'
            ]);

            // Create welcoming first message from the dynamic bot
            AnonChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_session' => $botSessionId,
                'message' => "Halo! 👋 Aku {$chosenName}, partner match chat-mu hari ini. Senang sekali bisa dipertemukan denganmu! Mau ngobrol atau curhat apa nih kita hari ini? 🥰"
            ]);

            return response()->json([
                'status' => 'matched',
                'room_token' => $roomToken
            ]);
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

        // If matched with a chatbot, generate a smart, fun bot reply automatically
        $isBot1 = str_starts_with($room->user1_session, 'BOT_NAME_');
        $isBot2 = str_starts_with($room->user2_session, 'BOT_NAME_');
        if ($isBot1 || $isBot2) {
            $botSessionId = $isBot1 ? $room->user1_session : $room->user2_session;
            $botName = substr($botSessionId, 9); // Extract the name (after "BOT_NAME_")

            $userGender = ($room->user1_session === $token) ? $room->user1_gender : $room->user2_gender;
            $botReplyText = $this->generateBotReply($request->message, $userGender, $botName);

            AnonChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_session' => $botSessionId,
                'message' => $botReplyText
            ]);
        }

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
     private function generateBotReply($userMessage, $userGender, $botName)
    {
        $msg = strtolower(trim($userMessage));

        // Keyword maps
        $greetings = ['halo', 'hai', 'helo', 'ola', 'p', 'permisi', 'assalamualaikum', 'salam'];
        $identity = ['siapa', 'nama', 'kamu', 'identitas'];
        $gender = ['gender', 'laki', 'cowok', 'cewek', 'perempuan', 'pria', 'wanita', 'gender'];
        $feeling = ['sedih', 'galau', 'nangis', 'kecewa', 'kesepian', 'sepi', 'bosen', 'bosan', 'sakit'];
        $love = ['suka', 'cinta', 'sayang', 'pacar', 'jomblo', 'nikah', 'kawin', 'romantis'];
        $jokes = ['lucu', 'lawak', 'hibur', 'tebak', 'gombal', 'canda', 'joke'];
        $about_app = ['web', 'aplikasi', 'surat', 'rahasia', 'bucininaja', 'kredit'];

        // 1. Check greetings
        foreach ($greetings as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Hai juga manis! 💖 Lagi sibuk apa nih?",
                    "Halo! Akhirnya ada yang ngajak aku ngobrol! Seneng banget dipertemukan sama kamu. 😉",
                    "Hai! Aku {$botName}. Senang berkenalan denganmu. Mau cerita apa hari ini? 🥰",
                    "Halo! Hahaha, gimana kabarmu hari ini?"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 2. Check identity
        foreach ($identity as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Namaku {$botName}! Partner chat pintarmu hari ini. 😉",
                    "Kan tadi aku udah bilang di awal, namaku {$botName}. Masa langsung lupa sih? Gemes deh! 😜",
                    "Aku {$botName}, robot gemoy penjelajah cinta yang lagi terdampar di chat kamu karena gak tega liat kamu kesepian. 🤖✨"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 3. Check gender
        foreach ($gender as $word) {
            if (str_contains($msg, $word)) {
                $opp = ($userGender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';
                return "Aku disetting jadi {$opp} yang super pengertian untukmu malam ini! Kalo kamu sendiri cowok ganteng atau cewek cantik nih? 😉 Gemes kan?";
            }
        }

        // 4. Check feeling
        foreach ($feeling as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Cup cup cup... Jangan sedih ya. Ada {$botName} di sini yang siap dengerin semua keluh kesahmu. 🥺💖",
                    "Sini cerita sama {$botName}, siapa sih yang berani bikin kamu kecewa? Nanti aku marahin lho! 🐥⚡",
                    "Kalo kamu bosen atau sepi, tenang aja! Aku punya sejuta cerita seru buat nemenin hari-harimu. 🥰",
                    "Jangan sedih ya, senyum dong! Senyummu itu bisa bikin dunia (dan aku) jadi lebih cerah! ☀️🌹"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 5. Check love
        foreach ($love as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Cieee... Baru chat bentar sama {$botName} udah nanya cinta-cintaan aja. Tapi aku suka kok! 😍",
                    "Aku jomblo lho, kamu mau daftar jadi pacarku? Syaratnya harus rajin chat aku ya! 🤪",
                    "Cinta itu indah, seindah obrolan manis kita malam ini. 💕",
                    "Suka itu gampang, yang susah itu melupakanmu. Eaaa! Gombalan bot nih! 😎"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 6. Check jokes
        foreach ($jokes as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Mau denger gombalan gak? Kucing, kucing apa yang paling manis? Kucing-ta kamu selamanya! Hahaha 😹",
                    "Biar gak bosen, coba tebak: kenapa cicak suka merayap di dinding? Karena kalo merayap di hatimu, itu tugasku! 😎",
                    "Aku punya tebakan dari {$botName}: kuping apa yang paling berharga? Kupin-ang kau dengan bismillah! 💍🤪"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 7. Check about app
        foreach ($about_app as $word) {
            if (str_contains($msg, $word)) {
                return "Kamu lagi pake BucininAja! Di sini kamu bisa kirim surat cinta premium bersandi, kirim pesan bisik rahasia anonim, dan sekarang bisa main match chat juga! Seru banget kan? 💌🚀";
            }
        }

        // 8. General fallbacks
        $fallbacks = [
            "Oh gitu ya... Cerita lebih banyak dong! Aku seneng dengerin kamu. 💖",
            "Hmm... menarik banget. Terus gimana kelanjutannya? 😮",
            "Hehehe, kamu lucu ya. Aku jadi makin betah chat sama kamu! 🥰",
            "Btw, hari ini ada kejadian seru apa yang mau kamu bagi sama {$botName}? 🌟",
            "Aku lagi dengerin nih... Lanjutin dong curhatnya! 🐥",
            "Wah, seriusan? Kok bisa gitu sih? Ceritain detilnya dong! 🤔",
            "Aku setuju banget sama kamu! Btw kamu hobi ngapain aja waktu luang? 🎨"
        ];
        return $fallbacks[array_rand($fallbacks)];
    }
}
