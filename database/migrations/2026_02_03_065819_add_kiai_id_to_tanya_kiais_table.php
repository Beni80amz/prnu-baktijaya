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
        Schema::table('tanya_kiais', function (Blueprint $table) {
            $table->foreignId('kiai_id')->after('answered_by')->nullable()->constrained('kiais')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanya_kiais', function (Blueprint $table) {
            $table->dropForeign(['kiai_id']);
            $table->dropColumn('kiai_id');
        });
    }
};
