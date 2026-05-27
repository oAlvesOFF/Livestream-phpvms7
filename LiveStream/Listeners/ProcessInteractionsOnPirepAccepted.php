<?php

namespace Modules\LiveStream\Listeners;

use App\Events\PirepAccepted;
use Illuminate\Support\Facades\DB;
use App\Services\JournalService;
use App\Models\Enums\JournalType;

class ProcessInteractionsOnPirepAccepted
{
    /**
     * Handle the event.
     *
     * @param  PirepAccepted  $event
     * @return void
     */
    public function handle(PirepAccepted $event)
    {
        $pirep = $event->pirep;
        $user = $pirep->user;

        // Sum all points/bonus money awarded during this flight
        $totalBonus = DB::table('passenger_interactions')
            ->where('pirep_id', $pirep->id)
            ->sum('points_awarded');

        if ($totalBonus > 0) {
            // Option 1: Add to their user balance/flight pay
            // Depending on phpVMS version, you use JournalService.
            
            try {
                $journalService = app(JournalService::class);
                $journal = $user->journal;
                if ($journal) {
                    $journalService->post(
                        $journal,
                        null,
                        $totalBonus,
                        null,
                        'Bônus de Satisfação dos Passageiros na Stream (PIREP: '.$pirep->id.')',
                        'LiveStream Bonus',
                        null
                    );
                }
            } catch (\Exception $e) {
                \Log::error("Failed to apply livestream bonus for PIREP {$pirep->id}: " . $e->getMessage());
            }

            // Option 2: Add pure points if you have a custom points column.
            // e.g. $user->flights_score += $totalBonus; $user->save();
        }
    }
}
