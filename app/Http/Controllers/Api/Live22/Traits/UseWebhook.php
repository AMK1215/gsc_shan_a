<?php

namespace App\Http\Controllers\Api\Live22\Traits;

use App\Enums\TransactionName;
use App\Enums\TransactionStatus;
use App\Enums\WagerStatus;
use App\Http\Requests\PlaceBetWebhookRequest;
use App\Models\Admin\GameType;
use App\Models\Admin\SeamlessEvent;
use App\Models\Admin\SeamlessTransaction;
use App\Models\Admin\Wager;
use App\Models\GameTypeProduct;
use App\Models\Product;
use App\Models\User;
use App\Services\Slot\Dto\RequestTransaction;
use App\Services\WalletService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait UseWebhook
{
    /**
     * Create a Seamless Event.
     *
     * @throws Exception
     */
    public function createEvent(PlaceBetWebhookRequest $request): SeamlessEvent
    {
        $user = $request->getMember();
        $gameType = $request->getGameTypeID();
        $gameList = $request->getGameListID();

        // Log the retrieved data
        Log::info('Creating SeamlessEvent with data', [
            'user' => $user ? $user->id : 'null',
            'gameType' => $gameType ? $gameType->id : 'null',
            'gameList' => $gameList ? $gameList->id : 'null',
        ]);

        if (! $user || ! $gameType || ! $gameList) {
            throw new Exception('Invalid data provided for creating Seamless Event.');
        }

        return SeamlessEvent::create([
            'user_id' => $user->id,
            'game_type_id' => $gameType->id,
            'game_list_id' => $gameList->id,
            'request_time' => $request->getRequestTime(),
            'raw_data' => $request->all(),
        ]);
    }

    /**
     * Create Wager Transactions.
     *
     * @throws MassAssignmentException
     * @throws Exception
     */
    public function createWagerTransactions(
        array $requestTransactions,
        SeamlessEvent $event,
        PlaceBetWebhookRequest $request,
        bool $refund = false
    ): array {
        $seamlessTransactions = [];

        foreach ($requestTransactions as $requestTransaction) {
            // Create or retrieve Wager
            $wager = Wager::firstOrCreate(
                ['seamless_wager_id' => $requestTransaction->WagerID],
                [
                    'user_id' => $event->user->id,
                    'seamless_wager_id' => $requestTransaction->WagerID,
                ]
            );

            if ($refund) {
                $wager->update([
                    'status' => WagerStatus::Refund,
                ]);
            } elseif (! $wager->wasRecentlyCreated) {
                $wager->update([
                    'status' => $requestTransaction->TransactionAmount > 0 ? WagerStatus::Win : WagerStatus::Lose,
                ]);
            }
            // Retrieve GameType
            $gameType = GameType::where('code', $requestTransaction->GameType)->first();
            if (! $gameType) {
                throw new Exception("Game type not found for code: {$requestTransaction->GameType}");
            }

            // Retrieve Product
            $product = Product::where('code', $requestTransaction->GameCode)->first();
            if (! $product) {
                throw new Exception("Product not found for code: {$requestTransaction->GameCode}");
            }

            // Retrieve GameTypeProduct relationship
            $gameTypeProduct = GameTypeProduct::where('game_type_id', $gameType->id)
                ->where('product_id', $product->id)
                ->first();
            if (! $gameTypeProduct) {
                throw new Exception("Game type product relation not found for Game Type ID {$gameType->id} and Product ID {$product->id}");
            }

            // Create SeamlessTransaction
            $seamlessTransaction = SeamlessTransaction::create([
                'seamless_event_id' => $event->id,
                'user_id' => $event->user_id,
                'wager_id' => $wager->id,
                'game_type_id' => $gameType->id,
                'product_id' => $product->id,
                'seamless_transaction_id' => $requestTransaction->TransactionID,
                'exchange_rate' => $gameTypeProduct->rate,
                'transaction_amount' => $requestTransaction->TransactionAmount,
                'bet_amount' => $requestTransaction->BetAmount,
                'valid_amount' => $requestTransaction->ValidBetAmount,
                'operator_id' => $request->getOperatorCode(),
                'request_date_time' => $request->getRequestTime(),
                'signature' => $request->getSign(),
                'player_id' => $request->getMemberName(),
                'currency' => $request->get('Currency'),
                'round_id' => $requestTransaction->RoundID,
                'bet_id' => $requestTransaction->BetID,
                'game_code' => $requestTransaction->GameCode,
                'game_type' => $requestTransaction->GameType,
                'tran_date_time' => $requestTransaction->TranDateTime,
                'auth_token' => $requestTransaction->AuthToken,
                'provider_time_zone' => $requestTransaction->ProviderTimeZone,
                'provider_tran_dt' => $requestTransaction->ProviderTranDt,
                'old_balance' => $requestTransaction->OldBalance ?? null,
                'new_balance' => $requestTransaction->NewBalance ?? null,
                'status' => TransactionStatus::Pending,
            ]);

            $seamlessTransactions[] = $seamlessTransaction;
        }

        return $seamlessTransactions;
    }

    /**
     * Process Wallet Transfer.
     *
     * @throws BindingResolutionException
     */
    public function processTransfer(
        User $from,
        User $to,
        TransactionName $transactionName,
        float $amount,
        int $rate,
        array $meta = []
    ): void {
        app(WalletService::class)->transfer(
            $from,
            $to,
            abs($amount),
            $transactionName,
            $meta
        );
    }
}
