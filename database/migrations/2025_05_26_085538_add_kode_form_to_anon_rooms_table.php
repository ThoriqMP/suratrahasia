<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('anon_rooms', function (Blueprint $table) {
            $table->string('kode_form', 8)->unique()->after('kode');
        });
    }

    public function down()
    {
        Schema::table('anon_rooms', function (Blueprint $table) {
            $table->dropColumn('kode_form');
        });
    }
};
