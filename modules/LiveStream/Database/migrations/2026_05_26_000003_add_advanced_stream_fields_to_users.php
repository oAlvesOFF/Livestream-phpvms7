<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * LEGACY — this migration previously added advanced stream columns to the core users table.
 * It is now a SAFE NO-OP. All stream data lives in the livestream_pilots table instead.
 * Kept so that artisan migrate:rollback does not break.
 */
return new class extends Migration
{
    public function up(): void
    {
        // NO-OP: fields moved to livestream_pilots table (module-owned)
    }

    public function down(): void
    {
        // Safe cleanup — only drop if the columns still exist (legacy installations)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'discord_webhook_url'))  $table->dropColumn('discord_webhook_url');
            if (Schema::hasColumn('users', 'obs_overlay_enabled'))  $table->dropColumn('obs_overlay_enabled');
        });
    }
};
