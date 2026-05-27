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
        $pirep = Pirep::with(['user', 'airline', 'arr_airport', 'dpt_airport', 'position'])
            ->where('id', $pirep_id)
            ->first();

        if (!$pirep) {
            return response("<html><body style='color:rgba(255,255,255,0.5); font-family:sans-serif; text-align:center; padding-top:20vh; font-size: 2rem;'>Voo offline ou terminado.</body></html>", 404);
        }

        $isActive = ($pirep->state == PirepState::IN_PROGRESS);

        return view('livestream::obs_overlay', [
            'pirep'    => $pirep,
            'user'     => $pirep->user,
            'isActive' => $isActive,
        ]);
    }
}
