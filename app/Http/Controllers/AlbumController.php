<?php

namespace App\Http\Controllers;

use App\Classes\Responses\ResponseStrings;
use App\Models\Album;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    //Create - post
    public function createAlbum(Request $request)
    {
        $validatedata = $request->validate([
            'name' => 'required|string|max:255|unique:album,name',
            'bio' => 'required|string|max:1500',
            'artist_id' => 'required|integer',
            //'thumbnail' => 'image|max:1999'
        ]);

        $user = auth()->user();
        $artist = Artist::where('id', $validatedata['artist_id'])->where('user_id', $user['id'])->first();
        if($artist)
        {
            $album = new Album([
                'name' => $validatedata['name'],
                'bio' => $validatedata['bio'],
                'artist_id' => $validatedata['artist_id'],
                'releaseDate' => Carbon::now()->toDateString(),
                //'thumbnail' => $validatedata['thumbnail'] Needs saving and referencing to location
            ]);
            $artist->albums()->save($album);
            $response =  new ValidResponse($album);
            return response()->json($response, 200);
        }
        $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
        return response()->json($response, 404);

    }

    //Read - get/id
    public function getAlbum(Request $request)
    {
        $validateData = $request->validate([
            'albumId' => '',
        ]);

        if (key_exists('albumId', $validateData))
        {
            $album = album::where('id', $validateData['albumId'])->get();
            if ($album)
            {
                return response()->json($album, 200);
            }
            $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
            return response()->json($response, 404);
        }
        else
        {
            $albums = Album::all();
            return response()->json($albums, 200);
        }
    }

    //Update - put
    public function updateAlbum(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'max:255',
            'bio' => 'max:1500',
            'album_id' => 'required|integer',
            'thumbnail' => 'image|max:1999'
        ]);

            $user = Auth::user();
            $album = Album::where('id', $validateData['album_id'])->first();
            $artist = Artist::where('id', $album['artist_id'])->where('user_id', $user['id'])->first();
            if ($album && $artist)
            {
                $artist->update($validateData);
                $artist->save();
                return response()->json($artist, 200);
            }
            $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
            return response()->json($response, 404);
        }

    //Delete - delete
    public function deleteAlbum(Request $request)
    {
        $validateData = $request->validate([
            'album_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $album = Album::where('id', $validateData['album_id'])->first();
        $artist = Artist::where('id', $album['artist_id'])->where('user_id', $user['id'])->first();
        if ($album && $artist)
        {
            $album->delete();
            $response = new ValidResponse(ResponseStrings::DELETED);
            return response()->json($response, 200);
        }
        $response = new InvalidResponse(ResponseStrings::NOT_FOUND);
        return response()->json($response, 404);
    }
}
