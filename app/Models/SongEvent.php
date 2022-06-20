<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongEvent extends Model
{
    protected $fillable = [
        'id',
        'song_id',
        'action_type',
        'name',
        'album_id',
        'genre',
        'resourceLocation',
        'releaseDate',
    ];

    protected $table = "song_events";
}
