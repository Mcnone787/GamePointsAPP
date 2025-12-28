<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Events\PlayerPointsUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function updatePoints(Request $request, Player $player)
    {
        $canUpdate = false;

        // 1. Es el creador de la sala
        if (Auth::check() && Auth::id() === $player->room->user_id) {
            $canUpdate = true;
        }
        // 2. Es el propio jugador logueado
        elseif (Auth::check() && Auth::id() === $player->user_id) {
            $canUpdate = true;
        }
        // 3. Es un invitado con la sesión correcta
        elseif (session('room_player_' . $player->room->code) == $player->id) {
            $canUpdate = true;
        }

        if (!$canUpdate) {
            abort(403, 'No tienes permiso para actualizar los puntos de este jugador.');
        }

        $request->validate([
            'points' => 'required|integer',
        ]);

        $player->points = $request->points;
        $player->save();

        // Emitir el evento para tiempo real
        broadcast(new PlayerPointsUpdated($player))->toOthers();

        return back()->with('success', 'Puntos actualizados correctamente.');
    }
}
