<?php

namespace App\Http\Controllers\Api\Live22;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashBonuRequest;
use App\Models\CashBonu;
use App\Models\User;
use App\Services\CashBonuWebhookService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashBonuController extends Controller
{
    public function cashBonu(CashBonuRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting CashBonu method');

            // Validate Player
            $player = $request->getMember();
            if (! $player) {
                Log::warning('Invalid player detected', [
                    'PlayerId' => $request->getPlayerId(),
                ]);

                return CashBonuWebhookService::buildResponse(
                    StatusCode::InvalidPlayerPassword,
                    0, // Balance is 0 in case of invalid player
                    0
                );
            }

            $oldBalance = $player->wallet->balance;
            Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

            $validator = $request->check();
            Log::info('Validator check passed');

            if ($validator->fails()) {
                Log::warning('Validation failed');

                return CashBonuWebhookService::buildResponse(
                    StatusCode::InvalidSignature,
                    0,
                    0
                );
            }

            // Check for duplicate transaction (based on TranId)
            $existingCashBonu = CashBonu::where('tran_id', $request->getTranId())->first();
            if ($existingCashBonu) {
                Log::warning('Duplicate TranId detected', [
                    'tran_id' => $request->getTranId(),
                ]);

                return CashBonuWebhookService::buildResponse(
                    StatusCode::DuplicateTransaction,
                    0, // Old balance should be 0 if a duplicate is found
                    0  // New balance should also be 0
                );
            }

            // Process the cash bonus transaction
            $newBalance = $oldBalance + $request->getPayout();
            CashBonu::create([
                'user_id' => $player->id,
                'tran_id' => $request->getTranId(),
                'bonus_id' => $request->getBonusId(),
                'bonus_name' => $request->getBonusName(),
                'result' => $request->getResult(),
                'currency' => $request->getCurrency(),
                'exchange_rate' => $request->getExchangeRate(),
                'payout' => $request->getPayout(),
                'player_id' => $request->getPlayerId(),
                'tran_date_time' => $request->getTranDateTime(),
                'provider_time_zone' => $request->getProviderTimeZone(),
                'provider_tran_dt' => $request->getProviderTranDt(),
                'operator_id' => $request->getOperatorId(),
                'request_date_time' => $request->getRequestDateTime(),
                'signature' => $request->getSignature(),
            ]);

            // Update player's wallet balance
            $player->wallet->balance = $newBalance;
            $player->wallet->save();

            Log::info('Updated member balance', ['new_balance' => $newBalance]);

            DB::commit();
            Log::info('CashBonu transaction committed successfully');

            return CashBonuWebhookService::buildResponse(
                StatusCode::OK,
                round($oldBalance, 4),  // Ensure old balance is rounded to 4 decimal places
                round($newBalance, 4)    // Ensure new balance is rounded to 4 decimal places
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to complete CashBonu transaction', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['message' => 'Failed to complete CashBonu transaction'], 500);
        }
    }
}
// class CashBonuController extends Controller
// {
//     public function cashBonu(CashBonuRequest $request)
//     {
//         DB::beginTransaction();
//         try {
//             Log::info('Starting CashBonu method');

//             // Validate Player
//             $player = $request->getMember();
//             if (! $player) {
//                 Log::warning('Invalid player detected', [
//                     'PlayerId' => $request->getPlayerId(),
//                 ]);

//                 return CashBonuWebhookService::buildResponse(
//                     StatusCode::InvalidPlayerPassword,
//                     0, // Balance is 0 in case of invalid player
//                     0
//                 );
//             }

//             $oldBalance = $player->wallet->balance;
//             Log::info('Retrieved member balance', ['old_balance' => $oldBalance]);

//             $validator = $request->check();
//             Log::info('Validator check passed');

//             if ($validator->fails()) {
//                 Log::warning('Validation failed');

//                 return CashBonuWebhookService::buildResponse(
//                     StatusCode::InvalidSignature,
//                     0,
//                     0
//                 );
//             }

//             // Check for duplicate transaction (based on TranId)
//             $existingCashBonu = CashBonu::where('tran_id', $request->getTranId())->first();
//             if ($existingCashBonu) {
//                 Log::warning('Duplicate TranId detected', [
//                     'tran_id' => $request->getTranId(),
//                 ]);

//                 return CashBonuWebhookService::buildResponse(
//                     StatusCode::DuplicateTransaction,
//                     0, // Old balance should be 0 if a duplicate is found
//                     0  // New balance should also be 0
//                 );
//             }

//             // Process the cash bonus transaction
//             $newBalance = $oldBalance + $request->getPayout();
//             CashBonu::create([
//                 'user_id' => $player->id,
//                 'tran_id' => $request->getTranId(),
//                 'bonus_id' => $request->getBonusId(),
//                 'bonus_name' => $request->getBonusName(),
//                 'result' => $request->getResult(),
//                 'currency' => $request->getCurrency(),
//                 'exchange_rate' => $request->getExchangeRate(),
//                 'payout' => $request->getPayout(),
//                 'player_id' => $request->getPlayerId(),
//                 'tran_date_time' => $request->getTranDateTime(),
//                 'provider_time_zone' => $request->getProviderTimeZone(),
//                 'provider_tran_dt' => $request->getProviderTranDt(),
//                 'operator_id' => $request->getOperatorId(),
//                 'request_date_time' => $request->getRequestDateTime(),
//                 'signature' => $request->getSignature(),
//             ]);

//             // Update player's wallet balance
//             $player->wallet->balance = $newBalance;
//             $player->wallet->save();

//             Log::info('Updated member balance', ['new_balance' => $newBalance]);

//             DB::commit();
//             Log::info('CashBonu transaction committed successfully');

//             return CashBonuWebhookService::buildResponse(
//                 StatusCode::OK,
//                 number_format((float) $oldBalance, 4, '.', ''),
//                 number_format((float) $newBalance, 4, '.', ''),

//                 //$oldBalance,
//                 //$newBalance
//             );
//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('Failed to complete CashBonu transaction', [
//                 'error' => $e->getMessage(),
//                 'line' => $e->getLine(),
//                 'file' => $e->getFile(),
//             ]);

//             return response()->json(['message' => 'Failed to complete CashBonu transaction'], 500);
//         }
//     }
// }
