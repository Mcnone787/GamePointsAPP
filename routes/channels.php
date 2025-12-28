<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal de la sala: solo pueden entrar si son jugadores de esa sala
Broadcast::channel('room.{code}', function ($user, $code) {
    $room = Room::where('code', $code)->first();
    
    if (!$room) {
        return false;
    }

    return $room->players()->where('user_id', $user->id)->exists();
});
