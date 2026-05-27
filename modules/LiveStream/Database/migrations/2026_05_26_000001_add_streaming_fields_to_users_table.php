<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * LEGACY — this migration previously added streaming columns to the core users table.
 * It is now a SAFE NO-OP. All stream data lives in the livestream_pilots table instead.
 * Kept so that artisan migrate:rollback does not break.
 */
class AddStreamingFieldsToUsersTable extends Migration
{
    public function up()
    {
        // NO-OP: fields moved to livestream_pilots table (module-owned)
    }

    public function down()
    {
        // Safe cleanup — only drop if the columns still exist (legacy installations)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'twitch_username'))    $table->dropColumn('twitch_username');
            if (Schema::hasColumn('users', 'youtube_channel_id')) $table->dropColumn('youtube_channel_id');
            if (Schema::hasColumn('users', 'is_live'))            $table->dropColumn('is_live');
            if (Schema::hasColumn('users', 'current_stream_url')) $table->dropColumn('current_stream_url');
        });
    }
}
