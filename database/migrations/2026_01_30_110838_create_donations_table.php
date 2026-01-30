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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('campaign_name')->nullable();
            $table->string('donor_name')->nullable();
            $table->string('donor_phone')->nullable();
            $table->text('donor_purpose')->nullable();
            $table->bigInteger('amount');
            $table->string('payment_method');
            $table->boolean('is_anonymous')->default(false);
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
