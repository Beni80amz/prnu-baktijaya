<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table for Live Attendance
        Schema::create('live_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable(); // e.g. "RW 02"
            $table->text('message')->nullable(); // e.g. "Mohon doa restu"
            $table->timestamps();
        });

        // Table for Live Chat
        Schema::create('live_chats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('message');
            $table->boolean('is_admin')->default(false);
            $table->string('avatar_color')->default('bg-slate-500'); // Store Tailwind class like 'bg-red-500'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_chats');
        Schema::dropIfExists('live_attendances');
    }
};
