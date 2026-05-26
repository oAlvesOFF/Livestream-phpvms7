<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiveStreamProfileController extends Controller
{
    /**
     * Show the LiveStream settings page using the ASA_THEME view.
     */
    public function index()
    {
        $user = Auth::user();

        // Try to get live Twitch status for preview (optional, won't break if fails)
        $twitchData = null;
        if (!empty($user->twitch_username)) {
            try {
                $clientId     = env('TWITCH_CLIENT_ID');
                $clientSecret = env('TWITCH_CLIENT_SECRET');
                if ($clientId && $clientSecret) {
                    $token = \Illuminate\Support\Facades\Http::post('https://id.twitch.tv/oauth2/token', [
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type'    => 'client_credentials',
                    ]);
                    if ($token->successful()) {
                        $accessToken = $token->json('access_token');
                        $stream = \Illuminate\Support\Facades\Http::withHeaders([
                            'Client-ID'     => $clientId,
                            'Authorization' => 'Bearer ' . $accessToken,
                        ])->get('https://api.twitch.tv/helix/streams?user_login=' . $user->twitch_username);
                        if ($stream->successful() && !empty($stream->json('data'))) {
                            $twitchData = $stream->json('data.0');
                        }
                    }
                }
            } catch (\Exception $e) {
                // Silently fail — no Twitch API available
            }
        }

        return view('profile.stream_settings', [
            'user'       => $user,
            'twitchData' => $twitchData,
        ]);
    }

    /**
     * Save stream settings — uses raw DB query to bypass mass-assignment restrictions.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'twitch_username'     => 'nullable|string|max:255',
            'youtube_channel_id'  => 'nullable|string|max:255',
            'discord_webhook'     => 'nullable|max:500',
            'obs_overlay_enabled' => 'nullable',
        ]);

        // Clean up Twitch: strip URL if user pasted full URL
        $twitch = $request->input('twitch_username', '');
        if ($twitch) {
            $twitch = preg_replace('/^https?:\/\/(www\.)?twitch\.tv\//', '', trim($twitch, '/'));
            $twitch = strtolower(trim($twitch));
        }

        $youtube    = trim($request->input('youtube_channel_id', '') ?? '');
        $webhook    = trim($request->input('discord_webhook', '') ?? '');
        $obsEnabled = $request->has('obs_overlay_enabled') ? 1 : 0;

        // Build update array only with columns that exist
        $updateData = [];

        // Always-present columns
        if (DB::getSchemaBuilder()->hasColumn('users', 'twitch_username')) {
            $updateData['twitch_username'] = $twitch ?: null;
        }
        if (DB::getSchemaBuilder()->hasColumn('users', 'youtube_channel_id')) {
            $updateData['youtube_channel_id'] = $youtube ?: null;
        }
        if (DB::getSchemaBuilder()->hasColumn('users', 'discord_webhook_url')) {
            $updateData['discord_webhook_url'] = $webhook ?: null;
        }
        if (DB::getSchemaBuilder()->hasColumn('users', 'obs_overlay_enabled')) {
            $updateData['obs_overlay_enabled'] = $obsEnabled;
        }

        if (!empty($updateData)) {
            DB::table('users')->where('id', $user->id)->update($updateData);
        }

        flash('✅ Configurações de Stream guardadas com sucesso!')->success();

        return redirect()->route('livestream.profile.index');
    }
}
