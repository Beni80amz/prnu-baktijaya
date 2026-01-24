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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name');
            $table->string('requester_phone')->nullable();
            $table->string('requester_email')->nullable();
            $table->enum('type', ['tahlil', 'yasin', 'istighotsah', 'doa_umum'])->default('doa_umum');
            $table->json('names'); // Names to pray for (almarhum/almarhumah)
            $table->text('notes')->nullable();
            $table->date('requested_date')->nullable(); // Scheduled date
            $table->enum('status', ['pending', 'scheduled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
