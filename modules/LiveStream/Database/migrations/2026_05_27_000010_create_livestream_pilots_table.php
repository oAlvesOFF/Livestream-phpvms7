<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * LiveStream Module — Standalone pilots table.
 * All stream data lives here. The core `users` table is NEVER modified.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('livestream_pilots')) {
            return;
        }

        Schema::create('livestream_pilots', function (Blueprint $table) {
            $table->id();

            // Reference to the phpVMS7 user — read-only FK, we never alter users schema
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Stream platform identifiers
            $table->string('twitch_username', 255)->nullable();
            $table->string('youtube_channel_id', 255)->nullable();

            // Live status — updated by the cron job / API
            $table->boolean('is_live')->default(false);
            $table->string('current_stream_url', 500)->nullable();

            // Integrations
            $table->string('discord_webhook_url', 500)->nullable();
            $table->boolean('obs_overlay_enabled')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestream_pilots');
    }
};
