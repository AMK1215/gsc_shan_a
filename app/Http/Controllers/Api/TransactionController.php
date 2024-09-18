<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\ReportTransaction;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        try {
            $input = $request->banker;
            $banker = $this->getUserByUsername($input['player_id']);
            if (! $banker) {
                return $this->error('', 'Not Found Banker', 401);
            }

            $this->handleBankerTransaction($banker, $input, $request->game_type_id);
            $results = [['player_id' => $banker->user_name, 'balance' => $banker->wallet->balance]];

            foreach ($request->players as $playerData) {
                $player = $this->getUserByUsername($playerData['player_id']);
                if ($player) {
                    $this->handlePlayerTransaction($player, $playerData, $request->game_type_id);
                    $results[] = ['player_id' => $player->user_name, 'balance' => $player->wallet->balance];
                }
            }

        } catch (\Exception $e) {
            return $this->error('Transaction failed', $e->getMessage(), 500);
        }

        return $this->success($results, 'Transaction Success');
    }

    private function getUserByUsername(string $username): ?User
    {
        return User::where('user_name', $username)->first();
    }

    private function handleBankerTransaction(User $banker, array $input, int $gameTypeId): void
    {
        ReportTransaction::create([
            'user_id' => $banker->id,
            'game_type_id' => $gameTypeId,
            'transaction_amount' => $input['amount'],
            'final_turn' => $input['is_final_turn'] ? 1 : 0,
            'banker' => 1,
        ]);

        if ($input['is_final_turn']) {
            $banker->wallet->balance += $input['amount'];
            $banker->wallet->save();
        }
    }

    private function handlePlayerTransaction(User $player, array $playerData, int $gameTypeId): void
    {
        ReportTransaction::create([
            'user_id' => $player->id,
            'game_type_id' => $gameTypeId,
            'transaction_amount' => $playerData['amount_changed'],
            'status' => $playerData['win_lose_status'],
            'bet_amount' => $playerData['bet_amount'],
            'valid_amount' => $playerData['bet_amount'],
        ]);

        $this->updatePlayerBalance($player, $playerData['amount_changed'], $playerData['win_lose_status']);
    }

    private function updatePlayerBalance(User $player, float $amountChanged, int $winLoseStatus): void
    {
        if ($winLoseStatus == 1) {
            $player->wallet->balance += $amountChanged;
        } else {
            $player->wallet->balance -= $amountChanged;
        }
        $player->wallet->save();
    }
}
