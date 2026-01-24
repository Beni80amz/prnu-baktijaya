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
        Schema::create('dawuhs', function (Blueprint $table) {
            $table->id();
            $table->text('quote'); // Indonesian quote
            $table->text('quote_arabic')->nullable(); // Arabic text
            $table->string('ulama_name');
            $table->string('ulama_title')->nullable(); // e.g., KH., Habib, Gus
            $table->string('source')->nullable(); // Book/reference
            $table->date('display_date')->nullable(); // Specific date to show
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dawuhs');
    }
};
