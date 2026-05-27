<?php

namespace Modules\LiveStream\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\Pirep;
use App\Models\Enums\PirepState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\LiveStream\Models\LiveStreamPilot;

class ProfileController extends Controller
{
    /**
     * Show the LiveStream profile settings form.
     * Loads the pilot's stream profile from the module-owned livestream_pilots table.
     */
    public function index()
    {
        $user = Auth::user();

        // Load (or initialise) the pilot's stream profile — never touches users table
        $streamProfile = LiveStreamPilot::forUser($user->id);

        // Fetch live Twitch data for preview if configured
        $twitchData = null;
        if (!empty($streamProfile->twitch_username)) {
            $clientId     = config('livestream.twitch_client_id',     env('TWITCH_CLIENT_ID'));
            $clientSecret = config('livestream.twitch_client_secret',  env('TWITCH_CLIENT_SECRET'));

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
                        ])->get('https://api.twitch.tv/helix/streams?user_login=' . $streamProfile->twitch_username);

                        if ($stream->successful() && !empty($stream->json('data'))) {
                            $twitchData = $stream->json('data.0');
                        }
                    }
                } catch (\Exception $e) {}
            }
        }

        // Find the pilot's currently active PIREP (IN_PROGRESS)
        $activePirep = Pirep::where('user_id', $user->id)
            ->where('state', PirepState::IN_PROGRESS)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('livestream::profile_settings', [
            'user'          => $user,
            'streamProfile' => $streamProfile,   // module-owned data
            'twitchData'    => $twitchData,
            'activePirep'   => $activePirep,
        ]);
    }

    /**
     * Save the stream settings to the module-owned livestream_pilots table.
     * The core users table is NOT touched.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'twitch_username'    => 'nullable|string|max:255',
            'youtube_channel_id' => 'nullable|string|max:255',
            'discord_webhook'    => 'nullable|string|max:500',
        ]);

        // Clean up Twitch: strip full URL if the user pasted it
        $twitch = $request->input('twitch_username');
        if ($twitch) {
            $twitch = preg_replace('/^https?:\/\/(www\.)?twitch\.tv\//', '', trim($twitch, '/'));
            $twitch = strtolower(trim($twitch));
        }

        $youtube    = trim($request->input('youtube_channel_id', '') ?? '');
        $webhook    = trim($request->input('discord_webhook', '') ?? '');
        $obsEnabled = $request->has('obs_overlay_enabled') ? true : false;

        // Upsert into the module-owned table — never writes to users
        LiveStreamPilot::updateOrCreate(
            ['user_id' => $user->id],
            [
                'twitch_username'     => $twitch    ?: null,
                'youtube_channel_id'  => $youtube   ?: null,
                'discord_webhook_url' => $webhook   ?: null,
                'obs_overlay_enabled' => $obsEnabled,
            ]
        );

        flash('✅ Configurações de Stream guardadas com sucesso!')->success();

        return redirect()->route('livestream.profile.index');
    }
}
