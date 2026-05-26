<?php

namespace Modules\LiveStream\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\Pirep;
use App\Models\Enums\PirepState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PassengerPanelController extends Controller
{
    /**
     * Show the passenger panel for a specific flight.
     */
    public function show($pirep_id)
    {
        $pirep = Pirep::with(['user', 'airline', 'aircraft', 'arr_airport', 'dep_airport', 'position'])
            ->where('id', $pirep_id)
            ->firstOrFail();

        $isActive = ($pirep->state == PirepState::IN_PROGRESS);

        $distance = optional($pirep->distance)->internal ?? ($pirep->distance ?? 0);
        $menuType = 'short';
        if ($distance > 2000) $menuType = 'long';
        elseif ($distance > 500) $menuType = 'medium';

        // Count interactions for this pirep
        $interactionCount = DB::table('passenger_interactions')
            ->where('pirep_id', $pirep->id)
            ->count();

        // Total satisfaction points
        $satisfactionPoints = DB::table('passenger_interactions')
            ->where('pirep_id', $pirep->id)
            ->sum('points_awarded');

        return view('livestream::passenger_panel', [
            'pirep'              => $pirep,
            'user'               => $pirep->user,
            'isActive'           => $isActive,
            'menuType'           => $menuType,
            'interactionCount'   => $interactionCount,
            'satisfactionPoints' => $satisfactionPoints,
        ]);
    }

    /**
     * Handle passenger interaction.
     */
    public function interact(Request $request, $pirep_id)
    {
        $pirep = Pirep::findOrFail($pirep_id);

        if ($pirep->state != PirepState::IN_PROGRESS) {
            return response()->json(['error' => 'Este voo já não está ativo.'], 400);
        }

        $ip        = $request->ip();
        $sessionId = $request->session()->getId();
        $type      = $request->input('type', 'cafe');

        $lastInteraction = DB::table('passenger_interactions')
            ->where('pirep_id', $pirep->id)
            ->where(function ($q) use ($ip, $sessionId) {
                $q->where('ip_address', $ip)
                  ->orWhere('session_id', $sessionId);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastInteraction) {
            $lastTime = Carbon::parse($lastInteraction->created_at);
            if ($lastTime->diffInMinutes(Carbon::now()) < 15) {
                $wait = 15 - $lastTime->diffInMinutes(Carbon::now());
                return response()->json(['error' => "Aguarda {$wait} min para interagir novamente."], 429);
            }
        }

        $messages = [
            'cafe'       => ['emoji' => '☕', 'text' => 'Café servido! O piloto agradece!', 'points' => 5],
            'sanduiche'  => ['emoji' => '🥪', 'text' => 'Sanduíche enviado! Bom apetite!', 'points' => 8],
            'jantar'     => ['emoji' => '🍝', 'text' => 'Jantar quente entregue! Excelente voo!', 'points' => 15],
            'champaigne' => ['emoji' => '🥂', 'text' => 'Champagne! Viagem de luxo!', 'points' => 20],
            'reclamacao' => ['emoji' => '😡', 'text' => 'Reclamação registada... O piloto foi notificado.', 'points' => -5],
            'aplauso'    => ['emoji' => '👏', 'text' => 'Aplausos para o Comandante! Excelente aterragem!', 'points' => 10],
        ];

        $data   = $messages[$type] ?? $messages['cafe'];
        $points = $data['points'];

        DB::table('passenger_interactions')->insert([
            'pirep_id'         => $pirep->id,
            'ip_address'       => $ip,
            'session_id'       => $sessionId,
            'interaction_type' => $type,
            'points_awarded'   => $points,
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $data['emoji'] . ' ' . $data['text'],
            'points'  => $points,
        ]);
    }
}
