<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'name',
        'points',
    ];

    /**
     * Get the room that owns the player.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user that owns the player.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
