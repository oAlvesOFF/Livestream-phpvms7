<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new stream-related columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'discord_webhook_url')) {
                $table->string('discord_webhook_url', 500)->nullable()->after('youtube_channel_id');
            }
            if (!Schema::hasColumn('users', 'obs_overlay_enabled')) {
                $table->boolean('obs_overlay_enabled')->default(false)->after('discord_webhook_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists('discord_webhook_url');
            $table->dropColumnIfExists('obs_overlay_enabled');
        });
    }
};
