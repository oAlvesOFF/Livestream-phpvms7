<?php

namespace Modules\LiveStream\Listeners;

use App\Events\PirepStateChange;
use App\Models\Enums\PirepState;
use Modules\LiveStream\Jobs\CheckLiveStatusJob;
use Modules\LiveStream\Models\LiveStreamPilot;

class CheckLiveOnStateChange
{
    /**
     * Handle the event.
     * All reads/writes go to the module-owned livestream_pilots table.
     * The core users table is never touched.
     */
    public function handle(PirepStateChange $event): void
    {
        $pirep  = $event->pirep;
        $userId = $pirep->user_id;

        // When the PIREP becomes IN_PROGRESS, check if the pilot is streaming
        if ($pirep->state == PirepState::IN_PROGRESS) {
            $streamProfile = LiveStreamPilot::where('user_id', $userId)->first();

            // Only dispatch if the pilot has streaming configured
            if ($streamProfile && (
                !empty($streamProfile->twitch_username) ||
                !empty($streamProfile->youtube_channel_id)
            )) {
                dispatch(new CheckLiveStatusJob($userId));
            }
        }

        // When PIREP is completed / cancelled / accepted → turn off live status
        elseif (in_array($pirep->state, [
            PirepState::PENDING,
            PirepState::CANCELLED,
            PirepState::ACCEPTED,
        ])) {
            LiveStreamPilot::where('user_id', $userId)
                ->where('is_live', true)
                ->update([
                    'is_live'            => false,
                    'current_stream_url' => null,
                ]);
        }
    }
}
