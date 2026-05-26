<?php

namespace Modules\LiveStream\Listeners;

use App\Events\ProfileUpdated;
use Illuminate\Support\Facades\DB;

class UpdateStreamSettings
{
    /**
     * Handle the event.
     *
     * @param ProfileUpdated $event
     * @return void
     */
    public function handle(ProfileUpdated $event)
    {
        $request = request();

        if ($request->has('twitch_username') || $request->has('youtube_channel_id')) {
            $twitch = $request->input('twitch_username');
            $youtube = $request->input('youtube_channel_id');

            // Clean inputs
            if ($twitch) {
                // If user pasted a full URL, extract the username
                $twitch = preg_replace('/^https?:\/\/(www\.)?twitch\.tv\//', '', $twitch);
                $twitch = trim($twitch, '/');
            }
            
            if ($youtube) {
                $youtube = trim($youtube);
            }

            \Illuminate\Support\Facades\Log::info("LiveStream: Updating user {$event->user->id} with Twitch: {$twitch}, YouTube: {$youtube}");

            DB::table('users')->where('id', $event->user->id)->update([
                'twitch_username' => $twitch,
                'youtube_channel_id' => $youtube
            ]);
        } else {
            \Illuminate\Support\Facades\Log::info("LiveStream: No twitch/youtube fields in request.");
        }
    }
}
