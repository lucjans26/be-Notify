<?php

namespace App\Http\Controllers;

use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Models\Album;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SongController extends Controller
{
    public function uploadSong(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'genre' => 'required|string',
            'album_id' => 'required',
            //'song' => 'required|file|max:5000',
            'song' => 'required|string',
        ]);

        $user = auth()->user();
        $album = Album::find($validateData['album_id']);
        $artist = $album->artist;

        if ($user->id == $artist->user_id)
        {
            $song = $artist->songs()->save([
                'title' => $validateData['title'],
                'genre' => $validateData['genre'],
                'album_id' => $validateData['album_id'],
                'song' => $validateData['song'],
            ]);

            $response = new ValidResponse($song);
            return response()->json($response, 201);
        }

        $response = new InvalidResponse('unauthorized');
        return response()->json($response, 401);

    }

    public function deleteSong(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required',
        ]);

        $user = auth()->user();
        $song = Song::find($validateData['song_id']);
        $album = $song->album;
        $artist = $album->artist;
        if ($user->id == $artist->user_id)
        {
            $song->delete();
            $response = new ValidResponse("song deleted");
            return response()->json($response, 200);
        }
        $response = new InvalidResponse('unauthorized');
        return response()->json($response, 401);
    }

    public function getSong(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'integer',
            'album_id' => 'integer',
        ]);

        if (isset($validateData['album_id']))
        {
            $album = Album::find($validateData['album_id']);
            if ($album)
            {
                $songs = $album->songs;
                $response = new ValidResponse($songs);
                return response()->json($response, 200);
            }
            $response = new InvalidResponse("not found");
            return response()->json($response, 404);
        }

        else if (isset($validateData['song_id']))
        {
            $song = Song::find($validateData['song_id']);
            if ($song)
            {
                $response = new ValidResponse($song);
                return response()->json($response, 200);
            }
            $response = new InvalidResponse('not found');
            return response()->json($response, 404);
        }

        $response = new InvalidResponse('invalid request');
        return response()->json($response, 400);
    }

}
