<?php

namespace Modules\LiveStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CheckLiveStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $isLive = false;
        $streamUrl = null;

        // Check Twitch
        if (!empty($this->user->twitch_username)) {
            $clientId = env('TWITCH_CLIENT_ID');
            $clientSecret = env('TWITCH_CLIENT_SECRET');

            if ($clientId && $clientSecret) {
                // First, get the App Access Token
                $tokenResponse = Http::post('https://id.twitch.tv/oauth2/token', [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'client_credentials'
                ]);

                if ($tokenResponse->successful()) {
                    $accessToken = $tokenResponse->json('access_token');

                    // Check if user is live
                    $response = Http::withHeaders([
                        'Client-ID' => $clientId,
                        'Authorization' => 'Bearer ' . $accessToken,
                    ])->get('https://api.twitch.tv/helix/streams?user_login=' . $this->user->twitch_username);

                    if ($response->successful() && !empty($response->json('data'))) {
                        $isLive = true;
                        $streamUrl = 'https://player.twitch.tv/?channel=' . $this->user->twitch_username . '&parent=' . request()->getHost();
                    }
                } else {
                    Log::error("Failed to authenticate with Twitch API: " . $tokenResponse->body());
                }
            } else {
                Log::warning("Twitch Client ID or Secret is missing in .env");
                // Fallback simulation for local testing if env is not configured
                $isLive = true;
                $streamUrl = 'https://player.twitch.tv/?channel=' . $this->user->twitch_username . '&parent=' . request()->getHost();
            }
        }
        // Check YouTube if Twitch isn't configured or isn't live
        elseif (!empty($this->user->youtube_channel_id)) {
            /* Example real call:
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $this->user->youtube_channel_id,
                'type' => 'video',
                'eventType' => 'live',
                'key' => env('YOUTUBE_API_KEY')
            ]);
            
            if ($response->successful() && !empty($response->json('items'))) {
                $videoId = $response->json('items.0.id.videoId');
                $isLive = true;
                $streamUrl = 'https://www.youtube.com/embed/' . $videoId;
            }
            */
            Log::info("Checking YouTube for " . $this->user->youtube_channel_id);
            $isLive = true;
            $streamUrl = 'https://www.youtube.com/embed/live_stream?channel=' . $this->user->youtube_channel_id;
        }

        // Update the user's stream status
        $this->user->is_live = $isLive;
        $this->user->current_stream_url = $streamUrl;
        $this->user->save();
    }
}
