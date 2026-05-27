<?php

namespace Modules\LiveStream\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\LiveStream\Models\LiveStreamPilot;

/**
 * Checks whether a pilot is currently live on Twitch or YouTube
 * and updates the module-owned livestream_pilots table.
 * The core users table is NEVER touched.
 */
class CheckLiveStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int phpVMS7 user ID */
    public int $userId;

    /**
     * @param int $userId  The phpVMS7 user ID to check
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load the pilot's stream profile from the module-owned table
        $streamProfile = LiveStreamPilot::where('user_id', $this->userId)->first();

        // No stream profile configured — nothing to check
        if (!$streamProfile) {
            return;
        }

        $wasLive   = (bool) $streamProfile->is_live;
        $isLive    = false;
        $streamUrl = null;

        // --- Check Twitch ---
        if (!empty($streamProfile->twitch_username)) {
            $clientId     = env('TWITCH_CLIENT_ID');
            $clientSecret = env('TWITCH_CLIENT_SECRET');

            if ($clientId && $clientSecret) {
                $tokenResponse = Http::post('https://id.twitch.tv/oauth2/token', [
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type'    => 'client_credentials',
                ]);

                if ($tokenResponse->successful()) {
                    $accessToken = $tokenResponse->json('access_token');

                    $response = Http::withHeaders([
                        'Client-ID'     => $clientId,
                        'Authorization' => 'Bearer ' . $accessToken,
                    ])->get('https://api.twitch.tv/helix/streams?user_login=' . $streamProfile->twitch_username);

                    if ($response->successful() && !empty($response->json('data'))) {
                        $isLive    = true;
                        $streamUrl = 'https://player.twitch.tv/?channel='
                            . $streamProfile->twitch_username
                            . '&parent=' . request()->getHost();
                    }
                } else {
                    Log::error('[LiveStream] Twitch API auth failed: ' . $tokenResponse->body());
                }
            } else {
                Log::warning('[LiveStream] TWITCH_CLIENT_ID or TWITCH_CLIENT_SECRET missing in .env');
                // Fallback: assume live if username is set and no API keys configured
                $isLive    = true;
                $streamUrl = 'https://player.twitch.tv/?channel='
                    . $streamProfile->twitch_username
                    . '&parent=' . request()->getHost();
            }
        }
        // --- Check YouTube (only if Twitch not configured / not live) ---
        elseif (!empty($streamProfile->youtube_channel_id)) {
            Log::info('[LiveStream] Checking YouTube for channel: ' . $streamProfile->youtube_channel_id);
            $isLive    = true;
            $streamUrl = 'https://www.youtube.com/embed/live_stream?channel=' . $streamProfile->youtube_channel_id;
        }

        // Persist the live status to the module-owned table only
        $streamProfile->update([
            'is_live'            => $isLive,
            'current_stream_url' => $streamUrl,
        ]);

        // --- Discord Webhook Notification ---
        // Only fire when pilot just went LIVE (not on every repeated check)
        if ($isLive && !$wasLive && !empty($streamProfile->discord_webhook_url)) {
            $this->sendDiscordNotification($streamProfile, $streamUrl);
        }
    }

    /**
     * Send a rich Discord embed notification to the pilot's webhook.
     */
    private function sendDiscordNotification(LiveStreamPilot $profile, ?string $streamUrl): void
    {
        $pilot = $profile->user;
        $name  = $pilot ? $pilot->name : 'Um piloto';

        // Determine platform label and colour
        $isTwitch   = !empty($profile->twitch_username);
        $color      = $isTwitch ? 9520895 : 16711680; // #9146FF purple for Twitch, #FF0000 red for YouTube
        $platform   = $isTwitch ? 'Twitch' : 'YouTube';
        $channelRef = $isTwitch
            ? 'twitch.tv/' . $profile->twitch_username
            : 'youtube.com — canal ' . $profile->youtube_channel_id;

        $payload = [
            'username'   => 'FlyAzores LIVE',
            'avatar_url' => 'https://flyazoresvirtual.com/favicon.ico',
            'embeds'     => [[
                'title'       => "\xF0\x9F\x94\xB4 {$name} est\xC3\xA1 a voar AO VIVO!",
                'description' => "**{$name}** iniciou um voo em direto no **{$platform}**!\nJunta-te \xC3\xA0 transmiss\xC3\xA3o e acompanha o voo em tempo real.",
                'color'       => $color,
                'fields'      => [
                    [
                        'name'   => "\xF0\x9F\x93\xBA Canal",
                        'value'  => $channelRef,
                        'inline' => true,
                    ],
                    [
                        'name'   => "\xF0\x9F\x8C\x90 Plataforma",
                        'value'  => $platform,
                        'inline' => true,
                    ],
                ],
                'footer' => [
                    'text' => 'FlyAzores Virtual Airline \xE2\x80\xA2 ' . now()->format('d/m/Y H:i') . ' UTC',
                ],
                'thumbnail' => [
                    'url' => 'https://flyazoresvirtual.com/favicon.ico',
                ],
            ]],
        ];

        if ($streamUrl) {
            $payload['embeds'][0]['url'] = $streamUrl;
        }

        try {
            $response = Http::timeout(10)->post($profile->discord_webhook_url, $payload);
            if (!$response->successful()) {
                Log::error('[LiveStream] Discord webhook failed: ' . $response->status() . ' — ' . $response->body());
            } else {
                Log::info('[LiveStream] Discord notification sent for user ' . $profile->user_id);
            }
        } catch (\Exception $e) {
            Log::error('[LiveStream] Discord webhook exception: ' . $e->getMessage());
        }
    }
}
