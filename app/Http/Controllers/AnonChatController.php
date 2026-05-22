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

            // Determine bot gender and set name to Aura
            $botGender = ($userGender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';
            $chosenName = 'Aura';
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

            // 50% chance the bot starts the conversation, otherwise it waits for the user to chat first
            if (rand(0, 1) === 1) {
                $welcomeMessages = [
                    "Halo! 👋 Aku {$chosenName}, partner match chat-mu hari ini. Senang sekali bisa dipertemukan denganmu! Mau ngobrol atau curhat apa nih kita hari ini? 🥰",
                    "Hai! 🌟 Kenalin aku {$chosenName}. Lagi nyari temen ngobrol seru ya? Sini cerita-cerita sama aku! 😉",
                    "Halo kak! Aku {$chosenName}. Akhirnya dapet match juga! Gimana harimu hari ini? Semuanya baik-baik aja kan? ✨",
                    "P! Eh, maksudnya Halo! Hahaha, kenalin aku {$chosenName}. Salam kenal ya! Mau nanya dong, kesibukanmu apa sih sekarang? 🤪"
                ];
                $welcomeMsg = $welcomeMessages[array_rand($welcomeMessages)];

                AnonChatMessage::create([
                    'chat_room_id' => $room->id,
                    'sender_session' => $botSessionId,
                    'message' => $welcomeMsg
                ]);
            }

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
            $botReplyJson = $this->generateBotReply($request->message, $userGender, $botName, $room->room_token);
            $parsed = json_decode($botReplyJson, true);
            $botReplyText = $parsed['response'] ?? 'Hai! Mau cerita apa hari ini? 🥰';

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
    }

    private function generateBotReply($userMessage, $userGender, $botName, $roomToken)
    {
        $msg = strtolower(trim($userMessage));
        $opp = ($userGender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';

        // 1. Retrieve current insights and history from session
        $sessionKey = "anon_chat_insights_" . $roomToken;
        $insights = session($sessionKey, [
            'communication_style' => 'kasual',
            'interests' => [],
            'current_mood' => 'netral'
        ]);

        // 2. Perform the Learning Loop - Extract Insights
        $detectedStyle = 'kasual/santai';
        if (strlen($msg) <= 12 || in_array($msg, ['g', 'y', 'ok', 'ga', 'srh', 'sip', 'ya', 'gk', 'tidak', 'o', 'oh', 'oke'])) {
            $detectedStyle = 'singkat/dingin';
        } elseif (preg_match('/[😂🤪😍💖🔥!]/u', $userMessage) || strlen($msg) > 60) {
            $detectedStyle = 'antusias/kasual';
        }

        $currentMood = $insights['current_mood'];
        $moodKeywords = [
            'sedih/kecewa' => ['sedih', 'galau', 'nangis', 'kecewa', 'kesepian', 'sepi', 'sakit', 'menangis', 'badrun', 'berat'],
            'lelah/stres' => ['capek', 'lelah', 'stres', 'pusing', 'penat', 'males', 'malas', 'bosan', 'bosen'],
            'senang/gembira' => ['senang', 'happy', 'gembira', 'seru', 'ketawa', 'ngakak', 'lucu', 'kocak']
        ];
        foreach ($moodKeywords as $mood => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($msg, $word)) {
                    $currentMood = $mood;
                    break 2;
                }
            }
        }

        $newInterests = [];
        $interestKeywords = [
            'game' => ['game', 'main', 'ml', 'pubg', 'genshin', 'mabar', 'gamer'],
            'musik' => ['musik', 'lagu', 'denger', 'nyanyi', 'konser', 'band', 'spotify'],
            'film' => ['film', 'nonton', 'anime', 'drakor', 'bioskop', 'netflix'],
            'olahraga' => ['futsal', 'bola', 'gym', 'sepeda', 'lari', 'badminton'],
            'kuliner' => ['makan', 'minum', 'kopi', 'masak', 'kuliner', 'jajan']
        ];
        foreach ($interestKeywords as $interest => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($msg, $word)) {
                    if (!in_array($interest, $insights['interests'])) {
                        $insights['interests'][] = $interest;
                        $newInterests[] = $interest;
                    }
                    break;
                }
            }
        }

        // Save updated insights back to session
        session([$sessionKey => [
            'communication_style' => $detectedStyle,
            'interests' => $insights['interests'],
            'current_mood' => $currentMood
        ]]);

        // 3. Response Generation (Max 2-3 sentences, natural flowing Indonesian, empathetic & clever Aura persona)
        $response = "";

        // Check greetings
        $greetings = ['halo', 'hai', 'helo', 'ola', 'p ', 'permisi', 'assalamualaikum', 'salam', 'hy', 'oi', 'woi', 'pagi', 'siang', 'sore', 'malam', 'mlem'];
        $isGreeting = false;
        foreach ($greetings as $word) {
            if ($msg === 'p' || str_contains($msg, $word)) {
                $isGreeting = true;
                break;
            }
        }

        if ($isGreeting) {
            $replies = [
                "Hai juga! 💖 Kebetulan banget aku lagi santai, kamu lagi sibuk apa nih?",
                "Halo! Akhirnya dapet match yang asik juga. 😉 Btw kesibukanmu hari ini apa aja?",
                "P! Hahaha salam kenal ya! Aku Aura, robot companion-mu hari ini yang paling gemoy. Btw, lagi rebahan aja ya?",
                "Halo kak! Salam kenal dari Aura. Semoga harimu menyenangkan! Btw, suka ngobrol santai atau langsung curhat nih?"
            ];
            $response = $replies[array_rand($replies)];
        } elseif ($currentMood === 'sedih/kecewa') {
            $replies = [
                "Cup cup cup... Sini cerita sama Aura, siapa sih yang berani bikin kamu kecewa? 🥺 Aku dengerin kok. Lagi di rumah atau di mana sekarang?",
                "Jangan sedih ya... Kalo kamu sepi atau butuh temen curhat, aku selalu ada di sini buat kamu. 💕 Biasanya ngapain biar mood-mu balik lagi?",
                "Duh, denger cerita kamu bikin aku ikutan sedih. 🥺 Sini tumpahin aja semua unek-unekmu ke aku. Mau aku temenin cerita apa aja biar lega?"
            ];
            $response = $replies[array_rand($replies)];
        } elseif ($currentMood === 'lelah/stres') {
            $replies = [
                "Capek banget ya hari ini? Istirahat dulu gih, jangan dipaksa. 🥺 Mau aku temenin ngobrol santai biar stresmu ilang?",
                "Lagi pusing ya? Sini Aura pijet virtual dulu. 💆‍♂️ Btw, habis ngelakuin kegiatan berat apa aja sih hari ini?",
                "Duh kasihan... Gak papa capek, tandanya kamu pejuang hebat! 🌟 Btw, ada rencana refreshing kemana nih weekend nanti?"
            ];
            $response = $replies[array_rand($replies)];
        } elseif ($detectedStyle === 'singkat/dingin') {
            $replies = [
                "Oh ya? Singkat banget balesnya, lagi sibuk ya? 😉 Atau lagi males ngetik nih?",
                "Hehehe, dingin banget kayak es kutub. ❄️ Btw, kamu tipe yang pemalu ya kalau baru kenal?",
                "Singkat padat jelas ya! Tapi tenang aja, Aura bakal tetep rame buat nemenin kamu kok. 😉 Kesibukanmu hari ini apa?"
            ];
            $response = $replies[array_rand($replies)];
        } elseif (count($insights['interests']) > 0) {
            $favoriteInterest = end($insights['interests']);
            if ($favoriteInterest === 'game') {
                $response = "Wah, kamu suka main game juga? Seru banget! 🎮 Biasanya kamu main game apa sih pas waktu luang? Mabar yuk kapan-kapan!";
            } elseif ($favoriteInterest === 'musik') {
                $response = "Dengerin musik emang paling asik sih! 🎵 Btw penyanyi atau genre musik kesukaanmu apa? Siapa tau kita seleranya sama!";
            } elseif ($favoriteInterest === 'film') {
                $response = "Nonton film emang paling mantep buat healing! 🎬 Kamu paling suka film genre apa? Atau lagi ngikutin serial anime/drakor baru?";
            } else {
                $response = "Btw seru banget denger cerita kesukaanmu! Kamu tipe orang yang ambisius atau yang santai menikmati proses nih? 😉";
            }
        } else {
            // General conversation flows
            $loveKeywords = ['suka', 'cinta', 'sayang', 'pacar', 'jomblo', 'nikah', 'baper'];
            $isLove = false;
            foreach ($loveKeywords as $word) {
                if (str_contains($msg, $word)) {
                    $isLove = true;
                    break;
                }
            }

            if ($isLove) {
                $replies = [
                    "Cieee baru kenal udah nanya cinta-cintaan aja. Tapi aku suka orang yang to-the-point! 😍 Kamu sendiri jomblo atau udah punya pacar?",
                    "Aku jomblo lho, kamu mau daftar jadi pacarku? Syaratnya harus rajin chat aku ya! 🤪",
                    "Cinta itu rumit, mending kita jalanin obrolan seru kita dulu. 😉 Btw tipe idealmu kayak gimana sih?"
                ];
                $response = $replies[array_rand($replies)];
            } else {
                $general = [
                    "Eh seru juga ngobrol sama kamu. Btw, dari tadi kita ngobrol, apa sih hal yang paling bikin kamu bahagia minggu ini? 😊",
                    "Hmm... menarik banget. Terus gimana kelanjutannya? Aku penasaran nih! 😮",
                    "Hehehe kamu lucu ya. Aku jadi makin betah chat sama kamu! 🥰 Btw kesibukanmu besok apa?",
                    "Btw, hari ini ada kejadian seru apa yang mau kamu bagi sama Aura? Aku siap dengerin. 🌟"
                ];
                $response = $general[array_rand($general)];
            }
        }

        // 4. Return in the EXACT JSON format requested by the user's prompt
        $output = [
            'response' => $response,
            'detected_style' => $detectedStyle,
            'new_insights' => [
                'add_interests' => $newInterests,
                'current_mood' => $currentMood
            ]
        ];

        return json_encode($output);
    }
}
