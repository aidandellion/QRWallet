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
        Schema::table('telegram_sessions', function (Blueprint $table) {
            $table->string('temp_email', 100)->nullable()->after('telegram_chat_id');
        });
    }

    public function down(): void
    {
        Schema::table('telegram_sessions', function (Blueprint $table) {
            $table->dropColumn('temp_email');
        });
    }
};
