<?php

namespace App\Http\Controllers;

use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ResponseStrings;
use App\Classes\Responses\ValidResponse;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function uploadSong(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|string|min:3|max:255|unique:song,name',
            'genre' => 'required|string',
            'album_id' => 'required',
            'song' => 'required|file|max:5000',
        ]);

        $user = auth()->user();
        $album = Album::find($validateData['album_id']);
        $artist = Artist::find($album->artist_id);

        if ($user->id == $artist->user_id)
        {
            $path = Storage::putFile('songs', $request->file('song'));

            $song = new Song([
                'name' => $validateData['title'],
                'genre' => $validateData['genre'],
                'album_id' => $validateData['album_id'],
                'resourceLocation' => $path,
                'releaseDate' => now(),
            ]);

            $album->songs()->save($song);

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
        $album = Album::find($song->album_id);
        $artist = Artist::find($album->artist_id);

        if ($user->id == $artist->user_id)
        {
            $song = Song::find($validateData['song_id']);
            Storage::delete($song->resourceLocation);
            $song->delete();
            $response = new ValidResponse(ResponseStrings::DELETED);
            return response()->json($response, 200);
        }
        $response = new InvalidResponse(ResponseStrings::UNAUTHORIZED);
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
            $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
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
            $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
            return response()->json($response, 404);
        }

        $response = new InvalidResponse(ResponseStrings::INVALID);
        return response()->json($response, 400);
    }

}
