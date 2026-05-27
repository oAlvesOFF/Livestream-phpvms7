<?php

namespace Modules\LiveStream\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Stores all LiveStream module data per pilot.
 * This model owns the `livestream_pilots` table — the core `users` table is NEVER modified.
 *
 * @property int     $id
 * @property int     $user_id
 * @property string  $twitch_username
 * @property string  $youtube_channel_id
 * @property bool    $is_live
 * @property string  $current_stream_url
 * @property string  $discord_webhook_url
 * @property bool    $obs_overlay_enabled
 */
class LiveStreamPilot extends Model
{
    protected $table = 'livestream_pilots';

    protected $fillable = [
        'user_id',
        'twitch_username',
        'youtube_channel_id',
        'is_live',
        'current_stream_url',
        'discord_webhook_url',
        'obs_overlay_enabled',
    ];

    protected $casts = [
        'is_live'            => 'boolean',
        'obs_overlay_enabled'=> 'boolean',
    ];

    /**
     * The phpVMS7 user that owns this stream profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Convenience: get or create the stream profile for a given user ID.
     */
    public static function forUser(int $userId): self
    {
        return static::firstOrNew(['user_id' => $userId]);
    }

    /**
     * Returns an array of user IDs currently marked as live.
     */
    public static function liveUserIds(): array
    {
        return static::where('is_live', true)->pluck('user_id')->toArray();
    }
}
