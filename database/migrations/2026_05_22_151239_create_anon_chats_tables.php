<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anon_chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_token')->unique();
            $table->string('user1_session');
            $table->string('user1_gender');
            $table->string('user2_session');
            $table->string('user2_gender');
            $table->string('status')->default('active'); // active, ended
            $table->timestamps();
        });

        Schema::create('anon_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('anon_chat_rooms')->onDelete('cascade');
            $table->string('sender_session');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('anon_chat_queue', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('gender');
            $table->string('status')->default('waiting'); // waiting, matched
            $table->foreignId('matched_room_id')->nullable()->constrained('anon_chat_rooms')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anon_chat_queue');
        Schema::dropIfExists('anon_chat_messages');
        Schema::dropIfExists('anon_chat_rooms');
    }
};
