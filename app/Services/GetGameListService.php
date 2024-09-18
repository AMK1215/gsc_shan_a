<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetGameListService
{
    protected $operatorId;

    protected $secretKey;

    protected $apiUrl;

    public function __construct()
    {
        $this->operatorId = config('game.api.operator_code');
        $this->secretKey = config('game.api.secret_key');
        $this->apiUrl = config('game.api.url').'GetGameList';
    }

    public function fetchGames()
    {
        $requestDateTime = now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $signature = $this->generateSignature($requestDateTime);

        // Log the details before making the request
        Log::info('Sending request to API', [
            'url' => $this->apiUrl,
            'OperatorId' => $this->operatorId,
            'RequestDateTime' => $requestDateTime,
            'Signature' => $signature,
        ]);

        $response = Http::post($this->apiUrl, [
            'OperatorId' => $this->operatorId,
            'RequestDateTime' => $requestDateTime,
            'Signature' => $signature,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            // Throw an exception with detailed error information
            throw new \Exception("Error fetching games: Status: {$response->status()}, URL: {$this->apiUrl}, Body: {$response->body()}");
        }
    }

    // public function fetchGames()
    // {
    //     $requestDateTime = now()->setTimezone('UTC')->format('Y-m-d H:i:s');
    //     $signature = $this->generateSignature($requestDateTime);

    //     $response = Http::post($this->apiUrl, [
    //         'OperatorId' => $this->operatorId,
    //         'RequestDateTime' => $requestDateTime,
    //         'Signature' => $signature
    //     ]);
    //     if ($response->successful()) {
    //     return $response->json();
    //     } else {
    //         Log::error('Failed to fetch game list', [
    //             'status' => $response->status(),
    //             'headers' => $response->headers(),
    //             'body' => $response->body()
    //         ]);
    //         throw new \Exception("Error fetching games: Status: {$response->status()}, Body: {$response->body()}");
    //     }

    // }

    protected function generateSignature($requestDateTime)
    {
        return md5('GetGameList'.$requestDateTime.$this->operatorId.$this->secretKey);
    }
}
