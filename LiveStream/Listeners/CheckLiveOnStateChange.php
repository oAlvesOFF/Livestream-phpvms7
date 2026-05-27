<?php

namespace Modules\LiveStream\Listeners;

use App\Events\PirepStateChange;
use App\Models\Enums\PirepState;
use Modules\LiveStream\Jobs\CheckLiveStatusJob;

class CheckLiveOnStateChange
{
    /**
     * Handle the event.
     *
     * @param  PirepStateChange  $event
     * @return void
     */
    public function handle(PirepStateChange $event)
    {
        $pirep = $event->pirep;

        // When the PIREP state becomes IN_PROGRESS, we check the pilot's live status
        if ($pirep->state == PirepState::IN_PROGRESS) {
            $user = $pirep->user;
            
            // Only dispatch if they have streaming usernames configured
            if (!empty($user->twitch_username) || !empty($user->youtube_channel_id)) {
                dispatch(new CheckLiveStatusJob($user));
            }
        }
        // When PIREP is completed/cancelled, turn off their live status
        elseif ($pirep->state == PirepState::PENDING || $pirep->state == PirepState::CANCELLED || $pirep->state == PirepState::ACCEPTED) {
            $user = $pirep->user;
            if ($user->is_live) {
                $user->is_live = false;
                $user->current_stream_url = null;
                $user->save();
            }
        }
    }
}
