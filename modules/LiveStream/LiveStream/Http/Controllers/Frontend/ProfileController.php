<?php

namespace Modules\LiveStream\Http\Controllers\Frontend;

use App\Contracts\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * Show the profile settings form for LiveStream.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch live Twitch data for preview if configured
        $twitchData = null;
        if (!empty($user->twitch_username)) {
            $clientId = config('livestream.twitch_client_id', env('TWITCH_CLIENT_ID'));
            $clientSecret = config('livestream.twitch_client_secret', env('TWITCH_CLIENT_SECRET'));
            if ($clientId && $clientSecret) {
                try {
                    $token = Http::post('https://id.twitch.tv/oauth2/token', [
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type'    => 'client_credentials',
                    ]);
                    if ($token->successful()) {
                        $accessToken = $token->json('access_token');
                        $stream = Http::withHeaders([
                            'Client-ID'     => $clientId,
                            'Authorization' => 'Bearer ' . $accessToken,
                        ])->get('https://api.twitch.tv/helix/streams?user_login=' . $user->twitch_username);
                        if ($stream->successful() && !empty($stream->json('data'))) {
                            $twitchData = $stream->json('data.0');
                        }
                    }
                } catch (\Exception $e) {}
            }
        }

        return view('livestream::profile_settings', [
            'user'       => $user,
            'twitchData' => $twitchData,
        ]);
    }

    /**
     * Save the stream settings.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'twitch_username'    => 'nullable|string|max:255',
            'youtube_channel_id' => 'nullable|string|max:255',
            'discord_webhook'    => 'nullable|url|max:500',
            'obs_overlay_enabled'=> 'nullable|boolean',
        ]);

        // Clean up twitch: strip URL if user pasted full URL
        $twitch = $request->input('twitch_username');
        if ($twitch) {
            $twitch = preg_replace('/^https?:\/\/(www\.)?twitch\.tv\//', '', trim($twitch, '/'));
            $twitch = strtolower(trim($twitch));
        }

        $youtube = trim($request->input('youtube_channel_id', '') ?? '');
        $webhook = trim($request->input('discord_webhook', '') ?? '');
        $obsEnabled = $request->has('obs_overlay_enabled') ? 1 : 0;

        // Use raw DB query to bypass mass-assignment restrictions
        DB::table('users')->where('id', $user->id)->update([
            'twitch_username'     => $twitch ?: null,
            'youtube_channel_id'  => $youtube ?: null,
            'discord_webhook_url' => $webhook ?: null,
            'obs_overlay_enabled' => $obsEnabled,
        ]);

        flash('✅ Configurações de Stream guardadas com sucesso!')->success();

        return redirect()->route('livestream.profile.index');
    }
}
