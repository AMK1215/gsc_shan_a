<?php

namespace App\Http\Controllers\Api\Live22;

use App\Enums\StatusCode;
use App\Enums\TransactionName;
use App\Http\Controllers\Controller;
use App\Http\Requests\RollBackWebhookRequest;
use App\Models\Admin\SeamlessTransaction;
use App\Models\User;
use App\Services\PlaceBetWebhookService;
use App\Services\RollBackWebhookService;
use App\Traits\UseWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RollBackController extends Controller
{
    use UseWebhook;

    public function rollBack(RollBackWebhookRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting RollBack method');

            // Validate Player
            $player = $request->getMember();
            if (! $player) {
                Log::warning('Invalid player detected', [
                    'PlayerId' => $request->getPlayerId(),
                ]);

                return RollBackWebhookService::buildResponse(
                    StatusCode::InvalidPlayerPassword,
                    0, // Balance is 0 in case of invalid player
                    0
                );
            }

            $oldBalance = $player->wallet->balance;
            Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

            // Validate the request
            $validator = $request->check();
            if ($validator->fails()) {
                Log::warning('Validation failed');

                return RollBackWebhookService::buildResponse(
                    StatusCode::InvalidSignature,
                    0,
                    0
                );
            }

            // Retrieve existing transaction balance
            $existingTransaction = SeamlessTransaction::where('bet_id', $request->getBetId())->first();
            if ($existingTransaction) {
                $existingBetAmount = $existingTransaction->bet_amount;
            } else {
                $existingBetAmount = 0;
                Log::warning('No existing transaction found for BetId', [
                    'BetId' => $request->getBetId(),
                ]);

                return RollBackWebhookService::buildResponse(
                    StatusCode::BetTransactionNotFound,
                    round($oldBalance, 4),
                    round($oldBalance, 4)
                );
            }

            // Check for duplicate RollbackType
            $existingRollback = SeamlessTransaction::where('bet_id', $request->getBetId())
                ->where('rollback_type', $request->getRollbackType())
                ->first();

            if ($existingRollback) {
                Log::warning('Duplicate Rollback BetId detected', [
                    'rollback_type' => $request->getRollbackType(),
                    'BetId' => $request->getBetId(),
                ]);

                return RollBackWebhookService::buildResponse(
                    StatusCode::DuplicateTransaction,
                    0,
                    0
                );
            }

            // Process the rollback transfer
            $this->processTransfer(
                $player,
                User::adminUser(),
                TransactionName::Rollback,
                $request->getBetAmount(),
                $request->getExchangeRate()
            );

            $newBalance = $player->wallet->refreshBalance()->balance;

            // Create the rollback transaction
            SeamlessTransaction::create([
                'user_id' => $request->getUserId(),
                'game_type_id' => $request->getGameTypeID(),
                'transaction_amount' => $request->getBetAmount(),
                'valid_amount' => $request->getBetAmount(),
                'operator_id' => $request->getOperatorCode(),
                'request_date_time' => $request->getRequestTime(),
                'signature' => $request->getSign(),
                'player_id' => $request->getPlayerId(),
                'currency' => $request->getCurrency(),
                'round_id' => $request->getRoundId(),
                'bet_id' => $request->getBetId(),
                'bet_amount' => $request->getBetAmount(),
                'exchange_rate' => $request->getExchangeRate(),
                'game_code' => $request->getGameCode(),
                'tran_date_time' => $request->getTranDateTime(),
                'auth_token' => $request->getAuthToken(),
                'provider_time_zone' => $request->getProviderTimeZone(),
                'provider_tran_dt' => $request->getProviderTranDt(),
                'old_balance' => round($oldBalance, 4),  // Round old balance
                'new_balance' => round($newBalance, 4),  // Round new balance
                'rollback_type' => $request->getRollbackType(),
            ]);

            Log::info('Refreshed member balance', ['new_balance' => $newBalance]);

            DB::commit();
            Log::info('Transaction committed successfully');

            return RollBackWebhookService::buildResponse(
                StatusCode::OK,
                round($oldBalance, 4),  // Ensure old balance is rounded
                round($newBalance + $existingBetAmount + $request->getBetAmount(), 4)  // Ensure new balance is rounded
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process rollback', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['message' => 'Failed to Rollback'], 500);
        }
    }
}

// class RollBackController extends Controller
// {
//     use UseWebhook;

//     public function rollBack(RollBackWebhookRequest $request)
//     {
//         DB::beginTransaction();
//         try {
//             Log::info('Starting RollBack method');

//             // Validate Player
//             $player = $request->getMember();
//             if (! $player) {
//                 Log::warning('Invalid player detected', [
//                     'PlayerId' => $request->getPlayerId(),
//                 ]);

//                 return RollBackWebhookService::buildResponse(
//                     StatusCode::InvalidPlayerPassword,
//                     0, // Balance is 0 in case of invalid player
//                     0
//                 );
//             }

//             $oldBalance = $player->wallet->balance;
//             Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

//             // Validate the request
//             $validator = $request->check();
//             if ($validator->fails()) {
//                 Log::warning('Validation failed');

//                 return RollBackWebhookService::buildResponse(
//                     StatusCode::InvalidSignature,
//                     0,
//                     0
//                 );
//             }

//             // Retrieve existing transaction balance
//             $existingTransaction = SeamlessTransaction::where('bet_id', $request->getBetId())->first();
//             if ($existingTransaction) {
//                 $existingBetAmount = $existingTransaction->bet_amount;
//             } else {
//                 $existingBetAmount = 0;
//                 Log::warning('No existing transaction found for BetId', [
//                     'BetId' => $request->getBetId(),
//                 ]);

//                 return RollBackWebhookService::buildResponse(
//                     StatusCode::BetTransactionNotFound,
//                     $oldBalance,
//                     $oldBalance
//                 );
//             }

//             // Check for duplicate RollbackType
//             $existingRollback = SeamlessTransaction::where('bet_id', $request->getBetId())
//                 ->where('rollback_type', $request->getRollbackType())
//                 ->first();

//             if ($existingRollback) {
//                 Log::warning('Duplicate Rollback BetId detected', [
//                     'rollback_type' => $request->getRollbackType(),
//                     'BetId' => $request->getBetId(),
//                 ]);

//                 return RollBackWebhookService::buildResponse(
//                     StatusCode::DuplicateTransaction,
//                     0,
//                     0
//                 );
//             }

//             // Process the rollback transfer
//             $this->processTransfer(
//                 $player,
//                 User::adminUser(),
//                 TransactionName::Rollback,
//                 $request->getBetAmount(),
//                 $request->getExchangeRate()
//             );

//             $newBalance = $player->wallet->refreshBalance()->balance;

//             // Create the rollback transaction
//             SeamlessTransaction::create([
//                 'user_id' => $request->getUserId(),
//                 'game_type_id' => $request->getGameTypeID(),
//                 'transaction_amount' => $request->getBetAmount(),
//                 'valid_amount' => $request->getBetAmount(),
//                 'operator_id' => $request->getOperatorCode(),
//                 'request_date_time' => $request->getRequestTime(),
//                 'signature' => $request->getSign(),
//                 'player_id' => $request->getPlayerId(),
//                 'currency' => $request->getCurrency(),
//                 'round_id' => $request->getRoundId(),
//                 'bet_id' => $request->getBetId(),
//                 'bet_amount' => $request->getBetAmount(),
//                 'exchange_rate' => $request->getExchangeRate(),
//                 'game_code' => $request->getGameCode(),
//                 'tran_date_time' => $request->getTranDateTime(),
//                 'auth_token' => $request->getAuthToken(),
//                 'provider_time_zone' => $request->getProviderTimeZone(),
//                 'provider_tran_dt' => $request->getProviderTranDt(),
//                 'old_balance' => $oldBalance,
//                 'new_balance' => $newBalance,
//                 'rollback_type' => $request->getRollbackType(),
//             ]);

//             Log::info('Refreshed member balance', ['new_balance' => $newBalance]);

//             DB::commit();
//             Log::info('Transaction committed successfully');

//             return RollBackWebhookService::buildResponse(
//                 StatusCode::OK,
//                 number_format((float) $oldBalance, 4, '.', ''),
//                 number_format((float) $newBalance + $existingBetAmount + $request->getBetAmount(), 4, '.', '')
//             );
//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('Failed to process rollback', [
//                 'error' => $e->getMessage(),
//                 'line' => $e->getLine(),
//                 'file' => $e->getFile(),
//             ]);

//             return response()->json(['message' => 'Failed to Rollback'], 500);
//         }
//     }
// }
