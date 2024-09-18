<?php

namespace App\Http\Controllers\Api\Live22;

use App\Enums\StatusCode;
use App\Enums\TransactionName;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameResultWebhookRequest;
use App\Http\Requests\PlaceBetWebhookRequest;
use App\Models\Admin\GameResult;
use App\Models\Admin\SeamlessTransaction;
use App\Models\User;
use App\Services\GameResultWebhookService;
use App\Services\GameResultWebhookValidator;
use App\Services\PlaceBetWebhookService;
use App\Traits\UseWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class GameResultController extends Controller
{
    use UseWebhook;

    public function gameResult(GameResultWebhookRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting GameResult method');

            // Validate Player
            $player = $request->getMember();
            if (! $player) {
                Log::warning('Invalid player detected', [
                    'PlayerId' => $request->getPlayerId(),
                ]);

                return GameResultWebhookService::buildResponse(
                    StatusCode::InvalidPlayerPassword,
                    0,
                    0
                );
            }

            $oldBalance = $player->wallet->balance;
            Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

            $validator = $request->check();
            if ($validator->fails()) {
                return $validator->getResponse();
            }

            // Get the transaction and check if it exists
            $existingTransaction = $request->transaction();
            if (! $existingTransaction || $existingTransaction->bet_id !== $request->GetBetID()) {
                Log::warning('BetId mismatch or not found in SeamlessTransaction', [
                    'request_bet_id' => $request->GetBetID(),
                    'transaction_bet_id' => $existingTransaction ? $existingTransaction->bet_id : null,
                ]);

                return GameResultWebhookService::buildResponse(
                    StatusCode::BetTransactionNotFound,
                    round($oldBalance, 4),
                    round($oldBalance, 4)
                );
            }

            // Check for duplicate ResultId
            $existingResult = GameResult::where('result_id', $request->getResultId())->first();
            if ($existingResult) {
                Log::warning('Duplicate ResultID detected', ['result_id' => $request->getResultId()]);

                return GameResultWebhookService::buildResponse(
                    StatusCode::DuplicateTransaction,
                    round($oldBalance, 4),
                    round($oldBalance, 4)
                );
            }

            // Process Transfer
            $this->processTransfer(
                $player,
                User::adminUser(),
                TransactionName::Stake,
                $request->getBetAmount(),
                $request->getExchangeRate()
            );

            $newBalance = $player->wallet->refreshBalance()->balance;
            // $player_agent = Auth::user();
            // $agentId = $player_agent->agent_id;

            GameResult::create([
                'user_id' => $request->getUserId(),
                //'agent_id' => $agentId,
                'operator_id' => $request->getOperatorCode(),
                'request_date_time' => $request->getRequestTime(),
                'signature' => $request->getSign(),
                'player_id' => $request->getPlayerId(),
                'currency' => $request->getCurrency(),
                'result_id' => $request->getResultId(),
                'bet_id' => $request->GetBetID(),
                'round_id' => $request->getRoundId(),
                'game_code' => $request->GetGameCode(),
                'game_type' => $request->GetGameType(),
                'game_name' => $request->input('GameName'),
                'result_type' => $request->GetResultType(),
                'bet_amount' => $request->getBetAmount(),
                'valid_bet_amount' => $request->input('ValidBetAmount'),
                'payout' => $request->getPayout(),
                'win_lose' => $request->getWinLose(),
                'exchange_rate' => $request->getExchangeRate(),
                'tran_date_time' => $request->getTranDateTime(),
                'provider_time_zone' => $request->getProviderTimeZone(),
                'provider_tran_dt' => $request->getProviderTranDt(),
                'round_type' => $request->getRoundType(),
            ]);

            Log::info('Refreshed member balance', ['new_balance' => $newBalance]);

            DB::commit();
            Log::info('Transaction committed successfully');

            $newBalance = round($oldBalance + $request->getPayout(), 4);  // Round to 4 decimal places

            return GameResultWebhookService::buildResponse(
                StatusCode::OK,
                round($oldBalance, 4),  // Ensure OldBalance is rounded to 4 decimal places
                round($newBalance, 4)  // Ensure NewBalance is rounded to 4 decimal places
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to game result', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['message' => 'Failed to game result'], 500);
        }
    }

    // public function gameResult(GameResultWebhookRequest $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         Log::info('Starting GameResult method');

    //         // Validate Player
    //         $player = $request->getMember();
    //         if (! $player) {
    //             Log::warning('Invalid player detected', [
    //                 'PlayerId' => $request->getPlayerId(),
    //             ]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::InvalidPlayerPassword,
    //                 0,
    //                 0
    //             );
    //         }

    //         $oldBalance = $player->wallet->balance;
    //         Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

    //         $validator = $request->check();
    //         if ($validator->fails()) {
    //             return $validator->getResponse();
    //         }

    //         // Get the transaction and check if it exists
    //         $existingTransaction = $request->transaction();
    //         if (! $existingTransaction || $existingTransaction->bet_id !== $request->GetBetID()) {
    //             Log::warning('BetId mismatch or not found in SeamlessTransaction', [
    //                 'request_bet_id' => $request->GetBetID(),
    //                 'transaction_bet_id' => $existingTransaction ? $existingTransaction->bet_id : null,
    //             ]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::BetTransactionNotFound,
    //                 $oldBalance,
    //                 $oldBalance
    //             );
    //         }

    //         // Check for duplicate ResultId
    //         $existingResult = GameResult::where('result_id', $request->getResultId())->first();
    //         if ($existingResult) {
    //             Log::warning('Duplicate ResultID detected', ['result_id' => $request->getResultId()]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::DuplicateTransaction,
    //                 $oldBalance,
    //                 $oldBalance
    //             );
    //         }

    //         // Process Transfer
    //         $this->processTransfer(
    //             $player,
    //             User::adminUser(),
    //             TransactionName::Stake,
    //             $request->getBetAmount(),
    //             $request->getExchangeRate()
    //         );

    //         $newBalance = $player->wallet->refreshBalance()->balance;
    //         GameResult::create([
    //             'user_id' => $request->getUserId(),
    //             'operator_id' => $request->getOperatorCode(),
    //             'request_date_time' => $request->getRequestTime(),
    //             'signature' => $request->getSign(),
    //             'player_id' => $request->getPlayerId(),
    //             'currency' => $request->getCurrency(),
    //             'result_id' => $request->getResultId(),
    //             'bet_id' => $request->GetBetID(),
    //             'round_id' => $request->getRoundId(),
    //             'game_code' => $request->GetGameCode(),
    //             'game_type' => $request->GetGameType(),
    //             'game_name' => $request->input('GameName'),
    //             'result_type' => $request->GetResultType(),
    //             'bet_amount' => $request->getBetAmount(),
    //             'valid_bet_amount' => $request->input('ValidBetAmount'),
    //             'payout' => $request->getPayout(),
    //             'win_lose' => $request->getWinLose(),
    //             'exchange_rate' => $request->getExchangeRate(),
    //             'tran_date_time' => $request->getTranDateTime(),
    //             'provider_time_zone' => $request->getProviderTimeZone(),
    //             'provider_tran_dt' => $request->getProviderTranDt(),
    //             'round_type' => $request->getRoundType(),
    //         ]);

    //         Log::info('Refreshed member balance', ['new_balance' => $newBalance]);

    //         DB::commit();
    //         Log::info('Transaction committed successfully');
    //         $new_balance = number_format($oldBalance + $request->getPayout(), 4, '.', '');

    //         return GameResultWebhookService::buildResponse(
    //             StatusCode::OK,
    //             number_format((float) $oldBalance, 4, '.', ''),
    //             number_format((float) $new_balance, 4, '.', '')
    //         );

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Failed to game result', [
    //             'error' => $e->getMessage(),
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile(),
    //         ]);

    //         return response()->json(['message' => 'Failed to game result'], 500);
    //     }
    // }

    // public function gameResult(GameResultWebhookRequest $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         Log::info('Starting GameResult method');

    //         // Validate Player
    //         $player = $request->getMember();
    //         if (! $player) {
    //             Log::warning('Invalid player detected', [
    //                 'PlayerId' => $request->getPlayerId(),
    //             ]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::InvalidPlayerPassword,
    //                 0,
    //                 0
    //             );
    //         }

    //         $oldBalance = $player->wallet->balance;
    //         Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

    //         $validator = $request->check();
    //         if ($validator->fails()) {
    //             return $validator->getResponse();
    //         }

    //         $existingTransaction = $request->transactionId();
    //         if (! $existingTransaction) {
    //             Log::warning('BetId not found in SeamlessTransaction', [
    //                 'bet_id' => $request->transactionId(),
    //                 'seamless_transactions_count' => SeamlessTransaction::count(),
    //                 'seamless_transactions_last_entry' => SeamlessTransaction::orderBy('created_at', 'desc')->first(),
    //             ]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::BetTransactionNotFound,
    //                 $oldBalance,
    //                 $oldBalance
    //             );
    //         }

    //         // Check for duplicate ResultId
    //         $existingResult = GameResult::where('result_id', $request->getResultId())->first();
    //         if ($existingResult) {
    //             Log::warning('Duplicate ResultID detected', ['result_id' => $request->getResultId()]);

    //             return GameResultWebhookService::buildResponse(
    //                 StatusCode::DuplicateTransaction,
    //                 $oldBalance,
    //                 $oldBalance
    //             );
    //         }

    //         // Process Transfer
    //         $this->processTransfer(
    //             $player,
    //             User::adminUser(),
    //             TransactionName::Stake,
    //             $request->getBetAmount(),
    //             $request->getExchangeRate()
    //         );

    //         $newBalance = $player->wallet->refreshBalance()->balance;
    //         GameResult::create([
    //             'user_id' => $request->getUserId(),
    //             'operator_id' => $request->getOperatorCode(),
    //             'request_date_time' => $request->getRequestTime(),
    //             'signature' => $request->getSign(),
    //             'player_id' => $request->getPlayerId(),
    //             'currency' => $request->getCurrency(),
    //             'result_id' => $request->getResultId(),
    //             'bet_id' => $request->transactionId(),
    //             'round_id' => $request->getRoundId(),
    //             'game_code' => $request->GetGameCode(),
    //             'game_type' => $request->GetGameType(),
    //             'game_name' => $request->input('GameName'),
    //             'result_type' => $request->GetResultType(),
    //             'bet_amount' => $request->getBetAmount(),
    //             'valid_bet_amount' => $request->input('ValidBetAmount'),
    //             'payout' => $request->getPayout(),
    //             'win_lose' => $request->getWinLose(),
    //             'exchange_rate' => $request->getExchangeRate(),
    //             'tran_date_time' => $request->getTranDateTime(),
    //             'provider_time_zone' => $request->getProviderTimeZone(),
    //             'provider_tran_dt' => $request->getProviderTranDt(),
    //             'round_type' => $request->getRoundType(),
    //         ]);

    //         Log::info('Refreshed member balance', ['new_balance' => $newBalance]);

    //         DB::commit();
    //         Log::info('Transaction committed successfully');

    //         return GameResultWebhookService::buildResponse(
    //             StatusCode::OK,
    //             $oldBalance,
    //             $newBalance
    //         );
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Failed to game result', [
    //             'error' => $e->getMessage(),
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile(),
    //         ]);

    //         return response()->json(['message' => 'Failed to game result'], 500);
    //     }
    // }
}
