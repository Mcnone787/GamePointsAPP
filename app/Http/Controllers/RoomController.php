<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Room;
use App\Events\PlayerJoined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $userRooms = Auth::user()->rooms()->latest()->paginate($perPage);

        $joinedRooms = Auth::user()->players()
            ->with('room')
            ->latest()
            ->get()
            ->pluck('room')
            ->where('user_id', '!=', Auth::id())
            ->unique('id')
            ->values()
            ->take($perPage);

        return Inertia::render('Dashboard', [
            'userRooms' => $userRooms,
            'joinedRooms' => $joinedRooms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'initial_points' => 'nullable|integer|min:1|max:1000',
        ]);

        $code = strtoupper(Str::random(5)); // Código en mayúsculas para mejor legibilidad

        $room = Auth::user()->rooms()->create([
            'name' => $request->name,
            'code' => $code,
            'initial_points' => $request->initial_points ?? 20,
        ]);

        $room->players()->create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'points' => $room->initial_points,
        ]);

        return redirect()->route('rooms.show', $room->code);
    }

    /**
     * Join a room.
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:5|exists:rooms,code',
            'name' => Auth::check() ? 'nullable' : 'required|string|max:255',
        ]);

        $room = Room::where('code', strtoupper($request->code))->firstOrFail();

        // Si está logueado, intentamos usar su usuario
        if (Auth::check()) {
            $player = $room->players()->where('user_id', Auth::id())->first();
            if (!$player) {
                $player = $room->players()->create([
                    'user_id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'points' => $room->initial_points ?? 20,
                ]);
                
                // Notificar a los demás que alguien se unió
                broadcast(new PlayerJoined($player))->toOthers();
            }
        } else {
            // Si es invitado, verificamos si ya tiene un jugador en esta sala en su sesión
            $sessionKey = 'room_player_' . $room->code;
            $playerId = session($sessionKey);
            $player = $playerId ? Player::find($playerId) : null;

            if (!$player) {
                $player = $room->players()->create([
                    'name' => $request->name,
                    'points' => $room->initial_points ?? 20,
                ]);
                // Guardar en sesión para recordarlo
                session([$sessionKey => $player->id]);

                // Notificar a los demás que alguien se unió
                broadcast(new PlayerJoined($player))->toOthers();
            }
        }

        return redirect()->route('rooms.show', $room->code);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('players');
        
        // Determinar quién es el jugador actual (Auth o Sesión)
        $currentUserPlayerId = null;
        if (Auth::check()) {
            $currentUserPlayerId = $room->players()->where('user_id', Auth::id())->first()?->id;
        } else {
            $currentUserPlayerId = session('room_player_' . $room->code);
        }

        // Si no es jugador, mostramos la vista de GameRoom pero el frontend manejará el "Unirse"
        return Inertia::render('Rooms/GameRoom', [
            'room' => $room,
            'players' => $room->players,
            'currentUserPlayerId' => (int) $currentUserPlayerId,
        ]);
    }
}
