<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the existing slider title if it matches the old one matches vaguely
        DB::table('sliders')
            ->where('title', 'like', '%Selamat Datang di Portal Resmi PRNU Baktijaya%')
            ->update([
                'title' => 'Selamat Datang di<br>Portal Resmi <span class="text-accent">PRNU Baktijaya</span>'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('sliders')
            ->where('title', 'like', '%Selamat Datang di<br>Portal%')
            ->update([
                'title' => 'Selamat Datang di Portal Resmi PRNU Baktijaya'
            ]);
    }
};
