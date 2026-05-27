<?php

namespace Modules\LiveStream\Listeners;

use App\Events\ProfileUpdated;
use Illuminate\Support\Facades\Log;
use Modules\LiveStream\Models\LiveStreamPilot;

class UpdateStreamSettings
{
    /**
     * Handle the event.
     * Syncs Twitch/YouTube from a core profile update into the module-owned
     * livestream_pilots table. The core users table is never touched.
     */
    public function handle(ProfileUpdated $event): void
    {
        $request = request();

        if (!$request->has('twitch_username') && !$request->has('youtube_channel_id')) {
            Log::info('[LiveStream] No twitch/youtube fields in request — skipping.');
            return;
        }

        $twitch  = $request->input('twitch_username');
        $youtube = $request->input('youtube_channel_id');

        // Strip full URL if the user pasted it
        if ($twitch) {
            $twitch = preg_replace('/^https?:\/\/(www\.)?twitch\.tv\//', '', $twitch);
            $twitch = strtolower(trim($twitch, '/'));
        }

        if ($youtube) {
            $youtube = trim($youtube);
        }

        Log::info("[LiveStream] Syncing stream settings for user {$event->user->id} — Twitch: {$twitch}, YouTube: {$youtube}");

        // Write only to the module-owned table
        LiveStreamPilot::updateOrCreate(
            ['user_id' => $event->user->id],
            [
                'twitch_username'    => $twitch  ?: null,
                'youtube_channel_id' => $youtube ?: null,
            ]
        );
    }
}
