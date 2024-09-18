<?php

namespace App\Http\Controllers\Api\Live22;

use App\Http\Controllers\Controller;
use App\Services\GetGameListService;
use Illuminate\Http\Request;

class GetGameListController extends Controller
{
    protected $gameListService;

    public function __construct(GetGameListService $gameListService)
    {
        $this->gameListService = $gameListService;
    }

    public function getGames()
    {
        try {
            $games = $this->gameListService->fetchGames();

            return response()->json($games);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
