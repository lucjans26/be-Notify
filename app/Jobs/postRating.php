<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class postRating implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $songId;
    private $type;
    private $userId;
    public function __construct($songId, $type, $userId)
    {
        $this->songId = $songId;
        $this->type = $type;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiURL = 'notify-rating/api/rating';
        $postInput = [
            'song_id' => $this->songId,
            'type' => $this->type,
            'user_id' => $this->userId
        ];

        $headers = [
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return response()->json($responseBody, $statusCode);
    }
}
