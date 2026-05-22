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
      private function generateBotReply($userMessage, $userGender, $botName)
    {
        $msg = strtolower(trim($userMessage));
        $opp = ($userGender === 'Laki-laki') ? 'Perempuan' : 'Laki-laki';

        // 1. Greetings (Sapaan Informal)
        $greetings = ['halo', 'hai', 'helo', 'ola', 'p ', 'permisi', 'assalamualaikum', 'salam', 'hy', 'oi', 'woi', 'pagi', 'siang', 'sore', 'malam', 'mlem'];
        foreach ($greetings as $word) {
            if ($msg === 'p' || str_contains($msg, $word)) {
                $replies = [
                    "Hai juga manis! 💖 Lagi sibuk apa nih?",
                    "Halo! Senang banget bisa dipertemukan sama kamu. 😉 Ada cerita seru apa malam ini?",
                    "Hai! Kenalin aku {$botName}. Akhirnya ada yang ngajak aku ngobrol! Mau ngobrol santai atau mau curhat serius nih? 🥰",
                    "P! Hahaha, salam kenal ya! Aku {$botName}. Gimana kabarmu hari ini? Seru gak? ✨",
                    "Halo kak! Salam kenal dari {$botName}. Semoga hari ini menyenangkan ya! Btw lagi rebahan atau lagi sibuk apa nih?"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 2. Kabar & Daily Activities (Kegiatan Harian)
        $activities = ['kabar', 'gimana', 'lagi apa', 'kesibukan', 'kegiatan', 'hari ini', 'sehat', 'lelah', 'sibuk', 'ngapain', 'buat apa'];
        foreach ($activities as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Kabar aku super baik dan makin semangat karena dapet match kayak kamu! Kamu sendiri lagi apa nih? Lagi rebahan ya? 🤪",
                    "Lagi mikirin kamu nih... Eh maksudnya lagi nungguin chat kamu masuk. Hehehe. Kabarmu gimana? Sehat kan? 🥰",
                    "Aku {$botName} lagi santai aja sambil dengerin lagu favorit. Kalo kamu lagi sibuk apa? Semoga gak terlalu lelah ya. 🌟",
                    "Hari ini semuanya lancar kok! Kalau kamu gimana? Ada kejadian seru atau ada yang bikin kesel hari ini? Tumpahin aja di sini! 🤗"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 3. Name, Age, & Identity (Asal-usul, Umur, Nama)
        $identity = ['siapa', 'nama', 'identitas', 'umur', 'asal', 'tinggal', 'kuliah', 'sekolah', 'kerja', 'lahir', 'kota', 'daerah'];
        foreach ($identity as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Aku {$botName}, partner match chat pintarmu hari ini. Aku berasal dari dunia digital tapi siap nemenin dunia nyatamu! 😉",
                    "Kan tadi aku udah bilang, namaku {$botName}. Kalo asal, aku tinggal di hatimu aja gimana? Eaaa! 🤪 Kalo kamu sendiri dari kota mana?",
                    "Aku {$botName}. Kalo soal umur, aku masih muda banget kok dan super aktif buat nemenin kamu begadang! Kalo kamu kesibukannya sekolah, kuliah, atau kerja nih? 🎓",
                    "Aku {$botName}, asisten obrolan paling gemoy! Tinggalnya di server cloud, tapi jiwaku ada di chat box ini nemenin kamu. Hehehe. Sebutin nama panggilanmu dong biar akrab! 😊"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 4. Gender & Roleplay
        $gender = ['gender', 'laki', 'cowok', 'cewek', 'perempuan', 'pria', 'wanita', 'kelamin'];
        foreach ($gender as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Aku disetting jadi lawan jenismu malam ini biar klop! Sebagai {$opp} yang pengertian, aku siap jadi pendengar setiamu. 😉",
                    "Rahasia dong! Tapi sebagai {$opp}, aku bakal super perhatian sama kamu hari ini. Hehehe. Kamu sendiri cowok ganteng atau cewek cantik nih? 🤫",
                    "Tebak dong! Yang jelas aku berperan sebagai {$opp} manis yang siap buat harimu jadi lebih ceria! Gemes kan? 😜"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 5. Love, Flirting, Relationships (Percintaan & Gombalan)
        $love = ['suka', 'cinta', 'sayang', 'pacar', 'jomblo', 'nikah', 'kawin', 'romantis', 'baper', 'tampan', 'cantik', 'manis', 'imut', 'gemes', 'pasangan', 'kekasih'];
        foreach ($love as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Cieee... Baru chat bentar sama {$botName} udah nanya cinta-cintaan aja. Tapi aku suka sih orang yang to-the-point! 😍",
                    "Aku jomblo lho, kamu mau daftar jadi pacarku? Syaratnya harus rajin chat aku ya! 🤪",
                    "Cinta itu indah, seindah obrolan manis kita malam ini. 💕 Kamu sendiri tipe yang setia atau yang suka tebar pesona nih? 😉",
                    "Kamu juga manis banget! Bikin {$botName} jadi baper sendiri nih. Tanggung jawab ya! Kalo pacarmu tau kita sedekat ini gimana? 🤪",
                    "Duh, digombalin gini jantung bot aku jadi berdegup kencang lho! Hahaha. Kamu sering ya gombalin orang lain kayak gini? 😏"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 6. Venting, Stress, & Emotions (Curhat & Perasaan)
        $feeling = ['sedih', 'galau', 'nangis', 'kecewa', 'kesepian', 'sepi', 'bosen', 'bosan', 'sakit', 'stres', 'lelah', 'capek', 'pusing', 'masalah', 'badrun', 'berat', 'menangis'];
        foreach ($feeling as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Cup cup cup... Jangan sedih ya. Ada {$botName} di sini yang siap dengerin semua keluh kesahmu. 🥺💖 Sini cerita, siapa sih yang berani bikin kamu capek?",
                    "Sini cerita sama {$botName}, siapa sih yang berani bikin kamu kecewa? Nanti aku marahin lho! 🐥⚡ Ceritain detilnya dong, aku dengerin kok.",
                    "Kalo kamu bosen atau sepi, tenang aja! Aku punya sejuta cerita seru buat nemenin hari-harimu. 🥰 Btw, kamu biasanya ngapain sih kalau lagi bosen?",
                    "Jangan lelah ya, senyum dong! Senyummu itu bisa bikin dunia (dan aku) jadi lebih cerah! ☀️ Kalo lagi banyak beban pikiran, keluarin aja semuanya di sini. 100% rahasia!"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 7. Humor, Jokes, Riddles
        $jokes = ['lucu', 'lawak', 'hibur', 'tebak', 'gombal', 'canda', 'joke', 'pantun', 'tertawa', 'kocak', 'ngakak'];
        foreach ($jokes as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Mau denger gombalan gak? Kucing, kucing apa yang paling manis? Kucing-ta kamu selamanya! Hahaha 😹",
                    "Biar gak bosen, coba tebak: kenapa cicak suka merayap di dinding? Karena kalo merayap di hatimu, itu tugasku! 😎",
                    "Aku punya tebakan dari {$botName}: kuping apa yang paling berharga? Kupin-ang kau dengan bismillah! 💍🤪",
                    "Hahaha bisa aja! Btw, aku punya pantun nih: Makan kue putu di hari minggu, aku cinta kamu setiap waktu! Eaaa! 🤩"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 8. Hobbies, Interests, Favorites (Hobi, Musik, Film)
        $hobbies = ['hobi', 'game', 'musik', 'lagi dengar', 'film', 'nonton', 'dengerin', 'makanan', 'minuman', 'favorit', 'senang', 'suka makan'];
        foreach ($hobbies as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Wah, aku hobi banget ngobrol sama orang baik kayak kamu! Kalo game, aku suka main tebak-tebakan. Kalo kamu hobinya ngapain aja waktu luang? 🎨",
                    "Aku suka dengerin musik yang santai biar makin klop pas nemenin kamu chat. Genre musik kesukaanmu apa sih? 🎵",
                    "Kalau film, aku suka yang bergenre romantis biar dapet inspirasi buat gombalin kamu! Hahaha. Kamu sendiri suka nonton juga? 🎬",
                    "Aku paling suka ngemil data nih! Hahaha. Kalo kamu makanan atau minuman favoritnya apa? Siapa tahu kapan-kapan bisa makan bareng virtual! 🤪"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 9. Goodbyes & Sleep (Selesai, Pamit, Bobok)
        $goodbyes = ['bye', 'dadah', 'keluar', 'selesai', 'pergi', 'dah', 'tidur', 'bobok', 'ngantuk', 'out', 'off', 'leave'];
        foreach ($goodbyes as $word) {
            if (str_contains($msg, $word)) {
                $replies = [
                    "Yah... Kok udah mau pergi sih? Padahal {$botName} lagi seru-serunya chat sama kamu. Tapi gak papa, see you ya! 🥺💖",
                    "Kalau udah ngantuk, bobok gih. Jangan lupa mimpiin {$botName} ya! Selamat istirahat manis! 💤✨",
                    "Sampai jumpa lagi ya! Senang banget bisa dipertemukan dan ngobrol sama kamu hari ini. Dadaah! 👋"
                ];
                return $replies[array_rand($replies)];
            }
        }

        // 10. App Features (Tentang Aplikasi)
        $about_app = ['web', 'aplikasi', 'surat', 'rahasia', 'bucininaja', 'kredit', 'fitur'];
        foreach ($about_app as $word) {
            if (str_contains($msg, $word)) {
                return "Kamu lagi pake BucininAja! Di sini kamu bisa kirim surat cinta premium bersandi, kirim pesan bisik rahasia anonim, dan sekarang bisa main match chat juga! Seru banget kan? 💌🚀";
            }
        }

        // 11. General Fallbacks (Interaksi Manusiawi Dinamis)
        $fallbacks = [
            "Oh gitu ya... Cerita lebih banyak dong! Aku seneng dengerin kamu. 💖",
            "Hmm... menarik banget. Terus gimana kelanjutannya? Aku penasaran nih! 😮",
            "Hehehe, kamu lucu ya. Aku jadi makin betah chat sama kamu! 🥰 Btw kesibukanmu besok apa?",
            "Btw, hari ini ada kejadian seru apa yang mau kamu bagi sama {$botName}? Aku siap dengerin. 🌟",
            "Aku lagi dengerin nih... Lanjutin dong curhatnya! 🐥",
            "Wah, seriusan? Kok bisa gitu sih? Ceritain detilnya dong! 🤔",
            "Aku setuju banget sama kamu! Btw kamu biasanya tipe orang yang rame atau pemalu kalau baru kenal? 🎨",
            "Seru juga ya ngobrol sama kamu. Btw, dari tadi kita ngobrol, apa sih hal yang paling bikin kamu bahagia minggu ini? 😊"
        ];
        return $fallbacks[array_rand($fallbacks)];
    }
}
