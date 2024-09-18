<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LaunchGameDemoService
{
    public function launchGameDemo(array $params)
    {
        $baseUrl = config('game.api.url'); // Ensure this config path is correctly defined
        $url = $this->buildDemoUrl($baseUrl, $params);

        Log::info("Redirecting to game demo URL: {$url}");

        return redirect()->away($url);
    }

    protected function buildDemoUrl(string $baseUrl, array $params)
    {
        $operatorId = config('game.api.operator_code');
        $currency = config('game.api.currency');

        $query = http_build_query([
            'opId' => $operatorId,
            'currency' => $currency,
            'gameCode' => $params['gameCode'],
            'redirectUrl' => $params['redirectUrl'] ?? config('game.default_redirect_url'),
            'lang' => $params['lang'] ?? 'en-us',
        ]);

        return "{$baseUrl}demo/LaunchGame?{$query}";
    }
}

// Controller Method
// public function launchGameDemoPlay(Request $request)
// {
//     $params = $request->only(['gameCode', 'redirectUrl', 'lang']);
//     return app(LaunchGameDemoService::class)->launchGameDemo($params);
// }

// class LaunchGameDemoService
// {
//     /**
//      * Build and redirect to the game demo URL.
//      *
//      * @param array $params
//      * @return RedirectResponse
//      */
//     public function launchGameDemo(array $params)
//     {
//         $baseUrl = config('game.api.url'); // Ensure this config path is correctly defined in your 'config/game.php'
//         $url = $this->buildDemoUrl($baseUrl, $params);

//         // Log the URL for auditing
//         Log::info("Redirecting to game demo URL: {$url}");

//         // Redirect to the constructed URL
//         return redirect()->away($url);
//     }

//     /**
//      * Build the demo game URL from parameters.
//      *
//      * @param string $baseUrl
//      * @param array $params
//      * @return string
//      */
//     protected function buildDemoUrl(string $baseUrl, array $params)
//     {
//         // Fetch operator ID and currency from configuration
//         $operatorId = config('game.api.operator_code');
//         $currency = config('game.api.currency');

//         // Ensure all required parameters are promotion
//         $requiredParams = ['gameCode']; // Assuming 'opId' and 'currency' are no longer required to be passed by params
//         foreach ($requiredParams as $param) {
//             if (!isset($params[$param])) {
//                 throw new \InvalidArgumentException("Missing required parameter: {$param}");
//             }
//         }

//         // Construct the query string
//         $query = http_build_query([
//             'opId' => $operatorId,
//             'currency' => $currency,
//             'gameCode' => $params['gameCode'],
//             //'redirectUrl' => $params['redirectUrl'],
//             //'lang' => $params['lang'] ?? 'en-us' // Default language to English if not specified
//         ]);

//         return "{$baseUrl}demo/LaunchGame?{$query}";
//     }
// }
