<?php

namespace App\Http\Controllers\Api\Live22;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameLoginRequest;
use App\Models\Admin\GameType;
use App\Models\GameList;
use App\Models\Product;
use App\Services\GameService;
use App\Services\LaunchGameDemoService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameLoginController extends Controller
{
    use HttpResponses;

    protected $gameService;
    //private const WEB_PLAT_FORM = 0;

    private const ENG_LANGUAGE_CODE = 'en-us';

    // Inject the GameService into the controller
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    // public function Gamelogin(GameLoginRequest $request)
    // {
    //     // Log incoming request data
    //     Log::info('Received game login request', $request->all());

    //     // Validate the required parameters
    //     $validated = $request->validate([
    //         'product_id' => 'required|integer',
    //         'game_type_id' => 'required|integer',
    //         'game_code' => 'required|string'
    //     ]);

    //     try {
    //         $response = $this->gameService->gameLogin(
    //             (int) $request->product_id,
    //             (int) $request->game_type_id,
    //             $request->game_code,
    //             (bool) $request->input('launch_demo', false)
    //         );

    //         return $this->success('Launch Game success', $response);
    //     } catch (\Throwable $e) {
    //         Log::error('Error processing game login', [
    //             'error' => $e->getMessage()
    //         ]);
    //         return $this->error('Game login failed', StatusCode::InternalServerError->value);
    //     }
    // }

    // public function launchGame(Request $request)
    // {
    //     // Log the incoming request data for debugging.
    //     Log::info('Received launch game request', $request->all());

    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         // 'productId' => 'required|integer',
    //         // 'gameType' => 'required|integer',
    //         'gameCode' => 'required|string',
    //         //'authToken' => 'required|string', // Assuming authToken is required
    //         //'lang' => 'sometimes|string',
    //         //'redirectUrl' => 'sometimes|url',
    //         'launchDemo' => 'sometimes|boolean',
    //     ]);

    //     // Configuration settings
    //     $operatorId = config('game.api.operator_code');
    //     $secretKey = config('game.api.secret_key');
    //     $apiUrl = config('game.api.url').'GameLogin';
    //     $currency = config('game.api.currency');
    //     $requestDateTime = now()->setTimezone('UTC')->format('Y-m-d H:i:s');
    //     $player = Auth::user();

    //     // Generate a signature for the API request
    //     $signature = md5('GameLogin'.$requestDateTime.$operatorId.$secretKey.$player->user_name);

    //     // Prepare the payload for the external API call
    //     $data = [
    //         'OperatorId' => $operatorId,
    //         'RequestDateTime' => $requestDateTime,
    //         'Signature' => $signature,
    //         'PlayerId' => $player->user_name,
    //         'Ip' => request()->ip(),
    //         'GameCode' => $validatedData['gameCode'],
    //         'Currency' => $currency,
    //         'DisplayName' => $player->name,
    //         'PlayerBalance' => $player->wallet->balance,
    //         'Lang' => $validatedData['lang'] ?? 'en-us',
    //         'RedirectUrl' => $validatedData['redirectUrl'] ?? 'https://operator-lobby-url.com',
    //         //'AuthToken' => $validatedData['authToken'],
    //         'LaunchDemo' => $validatedData['launchDemo'] ?? false,
    //     ];

    //     // Log internal data usage
    //     Log::info('Internal game launch parameters', [
    //         'ProductId' => $validatedData['productId'],
    //         'GameTypeId' => $validatedData['gameType'],
    //         'data' => $data, // Optionally log the outgoing data
    //     ]);

    //     try {
    //         // API request to external provider
    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //         ])->post($apiUrl, $data);

    //         if ($response->successful()) {
    //             return $response->json();
    //         }

    //         return response()->json(['error' => 'API request failed', 'details' => $response->body()], $response->status());
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => 'An unexpected error occurred', 'exception' => $e->getMessage()], 500);
    //     }
    // }

    public function Gamelogin(GameLoginRequest $request)
    {
        $response = $this->gameService->gameLogin(
            $request->game_code,
            $request->input('launch_demo', false)
        );

        return $this->success('Launch Game success', $response);
    }

    public function launchGameDemoPlay(Request $request)
    {
        $params = $request->only(['opId', 'currency', 'gameCode', 'redirectUrl', 'lang']);

        return app(LaunchGameDemoService::class)->launchGameDemo($params);
    }

    public function getGameList($productId, $gameTypeId)
    {
        $gamelist = Gamelist::where('product_id', $productId)->where('game_type_id', $gameTypeId)->get();

        return $this->success($gamelist);
    }

    public function getGameType()
    {
        $gameTypes = GameType::all();

        return $this->success($gameTypes);
    }

    public function getProductType($productId)
    {
        $products = Product::with('gameTypes')->where('id', $productId)->get();

        return $this->success($products);
    }

    public function GetHasDemo()
    {
        $gameList = GameList::where('has_demo', 1)->get();

        return $this->success($gameList);
    }
}
