<?php

namespace App\Http\Controllers\Admin\Live22;

use App\Http\Controllers\Controller;
use App\Models\Admin\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $results = GameResult::select(
            DB::raw('MAX(game_results.id) as id'),
            'game_results.user_id',
            'users.name as user_name',
            DB::raw('MAX(game_results.game_name) as game_name'),
            DB::raw('SUM(game_results.bet_amount) as total_bet_amount'),
            DB::raw('SUM(game_results.valid_bet_amount) as total_valid_bet_amount'),
            DB::raw('SUM(game_results.payout) as total_payout'),
            DB::raw('SUM(game_results.win_lose) as total_win_lose'),
            DB::raw('MAX(game_results.result_type) as result_type'),
            DB::raw('MAX(game_results.tran_date_time) as tran_date_time'),
        )
            ->join('users', 'game_results.user_id', '=', 'users.id')
            ->groupBy('game_results.user_id', 'users.name')
            ->orderBy('id', 'desc')
            ->get();

        // Pass the results to the view
        return view('admin.live_22.report.index', compact('results'));
    }

    public function show($id)
{
    // Get all game results for the specific user
    $gameResults = GameResult::where('user_id', $id)->get();

    // Pass the game results to the detail view
    return view('admin.live_22.report.show', compact('gameResults'));
}

// for agent live 22 report
    public function AgentReport()
{
    // Get the authenticated agent's ID
    $agentId = Auth::user()->id;

    // Fetch the game results associated with the authenticated agent
    $results = GameResult::select(
        DB::raw('MAX(game_results.id) as id'),
        'game_results.user_id',
        'users.name as user_name',
        DB::raw('MAX(game_results.game_name) as game_name'),
        DB::raw('SUM(game_results.bet_amount) as total_bet_amount'),
        DB::raw('SUM(game_results.valid_bet_amount) as total_valid_bet_amount'),
        DB::raw('SUM(game_results.payout) as total_payout'),
        DB::raw('SUM(game_results.win_lose) as total_win_lose'),
        DB::raw('MAX(game_results.result_type) as result_type'),
        DB::raw('MAX(game_results.tran_date_time) as tran_date_time')
    )
    ->join('users', 'game_results.user_id', '=', 'users.id')
    // Filter results by the authenticated agent's ID
    ->where('users.agent_id', $agentId)
    ->groupBy('game_results.user_id', 'users.name')
    ->orderBy('id', 'desc')
    ->get();

    return view('admin.live_22.report.agent_report.index', compact('results'));
}

// public function AgentReport()
// {
//     // Get the authenticated agent's ID
//     $agentId = Auth::user()->id;

//     // Fetch the game results associated with the authenticated agent
//     $results = GameResult::select(
//         DB::raw('MAX(game_results.id) as id'),
//         'game_results.user_id',
//         'users.name as user_name',
//         DB::raw('MAX(game_results.game_name) as game_name'),
//         DB::raw('SUM(game_results.bet_amount) as total_bet_amount'),
//         DB::raw('SUM(game_results.valid_bet_amount) as total_valid_bet_amount'),
//         DB::raw('SUM(game_results.payout) as total_payout'),
//         DB::raw('SUM(game_results.win_lose) as total_win_lose'),
//         DB::raw('MAX(game_results.result_type) as result_type'),
//         DB::raw('MAX(game_results.tran_date_time) as tran_date_time')
//     )
//     ->join('users', 'game_results.user_id', '=', 'users.id')
//     // Filter results by the authenticated agent's ID
//     ->where('game_results.agent_id', $agentId)
//     ->groupBy('game_results.user_id', 'users.name')
//     ->orderBy('id', 'desc')
//     ->get();

//     return view('admin.live_22.report.agent_report.index', compact('results'));
// }


}