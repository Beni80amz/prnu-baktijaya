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
        Schema::create('tanya_kiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('category', ['ibadah', 'muamalah', 'keluarga', 'akhlak', 'aqidah', 'lainnya'])->default('lainnya');
            $table->text('question');
            $table->longText('answer')->nullable();
            $table->enum('status', ['pending', 'answered', 'published', 'rejected'])->default('pending');
            $table->boolean('is_public')->default(false); // Show publicly
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanya_kiais');
    }
};
