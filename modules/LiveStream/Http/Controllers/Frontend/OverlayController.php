<?php

namespace Modules\LiveStream\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\Pirep;
use App\Models\Enums\PirepState;

class OverlayController extends Controller
{
    /**
     * OBS Browser Source overlay — no auth needed, transparent background.
     */
    public function show($pirep_id)
    {
        $pirep = Pirep::with(['user', 'airline', 'arr_airport', 'dep_airport', 'position'])
            ->where('id', $pirep_id)
            ->firstOrFail();

        $isActive = ($pirep->state == PirepState::IN_PROGRESS);

        return view('livestream::obs_overlay', [
            'pirep'    => $pirep,
            'user'     => $pirep->user,
            'isActive' => $isActive,
        ]);
    }
}
