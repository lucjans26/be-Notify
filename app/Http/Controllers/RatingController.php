<?php

namespace App\Http\Controllers;


use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Models\Rating;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class RatingController extends Controller
{
    public function like(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required|integer',
            'type' => 'required|integer|in:-1,0,1',
        ]);

        $user = auth()->user();

        $apiURL = '127.0.0.1:8081/api/rating';
        $postInput = [
            'song_id' => $validateData['song_id'],
            'type' => $validateData['type'],
            'user_id' => $user->id
        ];

        $headers = [
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return response()->json($responseBody, $statusCode);
    }

    public function getRating(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required|integer'
        ]);

        $user = auth()->user();

        $apiURL = '127.0.0.1:8081/api/rating';
        $params = [
                'song_id' => $validateData['song_id'],
                'user_id' => $user->id
        ];

        $headers = [
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->get($apiURL, $params);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return response()->json($responseBody, $statusCode);
    }
}
