<?php

namespace App\Http\Controllers;


use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Models\Rating;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RatingController extends Controller
{
    public function like(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required|integer',
            'type' => 'required|integer|in:-1,0,1',
        ]);

        $user = auth()->user();

        $rating = Rating::updateOrCreate(
                ['user_id' => $user->id, 'song_id' => $validateData['song_id']],
                ['value' => $validateData['type']]);

        $response = new ValidResponse($rating);
        return response()->json($response, 200);
    }

    public function getRating(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required|integer'
        ]);

        $user = auth()->user();
        $song = Song::find($validateData['song_id']);

        $ratings = $song->ratings()->get();
        $likes = $ratings->where('value', 1)->count();
        $dislikes = $ratings->where('value', -1)->count();
        $userRating = $ratings->where('user_id', $user->id)->where('song_id', $validateData['song_id'])->first();
        $response = new ValidResponse([
            'likes' => $likes,
            'dislikes' => $dislikes,
            'user_rating' => $userRating['value'] ?? null
        ]);

        return response()->json($response, 200);
    }
}
