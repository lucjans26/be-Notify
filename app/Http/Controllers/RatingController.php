<?php

namespace App\Http\Controllers;


use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Jobs\postRating;
use App\Models\Rating;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RatingController extends Controller
{
    public function like(Request $request)
    {
        $validateData = $request->validate([
            'song_id' => 'required|integer',
            'type' => 'required|integer|in:-1,0,1',
        ]);
        $userId = 1;
        //        $user = auth()->user();
        postRating::dispatch($validateData['song_id'], $validateData['type'], $userId);


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

    public function testMessage()
    {


    }
}
