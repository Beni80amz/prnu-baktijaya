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
        Schema::create('mosques', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['masjid', 'musholla', 'langgar'])->default('masjid');
            $table->text('address');
            $table->string('village')->nullable(); // Desa/Kelurahan
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('takmir_name')->nullable(); // Pengurus
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('has_wudu_facility')->default(true);
            $table->boolean('has_parking')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mosques');
    }
};
