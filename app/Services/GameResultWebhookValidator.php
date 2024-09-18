<?php

namespace App\Services;

use App\Enums\SlotWebhookResponseCode;
use App\Enums\StatusCode;
use App\Http\Requests\GameResultWebhookRequest;
use App\Models\Admin\SeamlessTransaction;
use App\Models\Admin\Wager;
use Illuminate\Support\Facades\Log;

class GameResultWebhookValidator
{
    protected ?SeamlessTransaction $existingTransaction;

    // TODO: imp: chang with actual wager
    protected ?Wager $existingWager;

    protected float $totalTransactionAmount = 0;

    protected float $before_balance;

    protected float $after_balance;

    protected array $response;

    /**
     * @var RequestTransaction[]
     */
    protected $requestTransactions;

    protected function __construct(protected GameResultWebhookRequest $request) {}

    public function validate()
    {
        if (! $this->isValidSignature()) {
            return $this->response(StatusCode::InvalidSignature);
        }

        if (! $this->request->getMember()) {
            return $this->response(StatusCode::InvalidPlayer);
        }

        // foreach ($this->request->getTransactions() as $transaction) {
        //     // Ensure that all required parameters are available
        //     $requestTransaction = new RequestTransaction(
        //         $transaction['Status'],
        //         $transaction['ProductID'] ?? null, // Set to null if not provided
        //         $transaction['GameCode'],
        //         $transaction['GameType'],
        //         $transaction['BetId'],
        //         $transaction['TransactionID'] ?? null, // Set to null if not provided
        //         $transaction['WagerID'] ?? null, // Set to null if not provided
        //         $transaction['BetAmount'] ?? null,
        //         $transaction['TransactionAmount'] ?? null,
        //         $transaction['PayoutAmount'] ?? null,
        //         $transaction['ValidBetAmount'] ?? null,
        //     );

        //     $this->requestTransactions[] = $requestTransaction;

        //     if ($requestTransaction->TransactionID && !$this->isNewTransaction($requestTransaction)) {
        //         return $this->response(StatusCode::DuplicateTransaction);
        //     }

        //     if (!in_array($this->request->getMethodName(), ['GetBalance', 'Bet', 'BuyIn', 'BuyOut']) && $this->isNewWager($requestTransaction)) {
        //         return $this->response(StatusCode::BetTransactionNotFound);
        //     }

        //     $this->totalTransactionAmount += $requestTransaction->TransactionAmount;
        //}

        if (! $this->hasEnoughBalance()) {
            return $this->response(StatusCode::InsufficientBalance);
        }

        return $this;
    }

    protected function isValidSignature()
    {
        //MD5 (FunctionName + ResultId + RequestDateTime + OperatorId + SecretKey + PlayerId)

        $method = $this->request->getMethodName();
        $resultId = $this->request->getResultId();
        $requestTime = $this->request->getRequestTime();
        $operatorCode = $this->request->getOperatorCode();
        $secretKey = $this->getSecretKey();
        $playerId = $this->request->getMemberName();

        // Log the values used for signature generation
        Log::info('Generating signature', [
            'method' => $method,
            'result_id' => $resultId,
            'requestTime' => $requestTime,
            'operatorCode' => $operatorCode,
            'secretKey' => $secretKey,
            'playerId' => $playerId,
        ]);
        // Generate the signature
        $signature = md5($method.$resultId.$requestTime.$operatorCode.$secretKey.$playerId);

        Log::info('Generated signature', ['signature' => $signature]);

        return $this->request->getSign() === $signature;
    }

    protected function isNewWager(RequestTransaction $transaction)
    {
        return ! $this->getExistingWager($transaction);
    }

    public function getExistingWager(RequestTransaction $transaction)
    {
        if (! isset($this->existingWager)) {
            $this->existingWager = Wager::where('seamless_wager_id', $transaction->WagerID)->first();
        }

        return $this->existingWager;
    }

    protected function isNewTransaction(RequestTransaction $transaction)
    {
        return ! $this->getExistingTransaction($transaction);
    }

    public function getExistingTransaction(RequestTransaction $transaction)
    {
        if (! isset($this->existingTransaction)) {
            $this->existingTransaction = SeamlessTransaction::where('seamless_transaction_id', $transaction->TransactionID)->first();
        }

        return $this->existingTransaction;
    }

    public function getAfterBalance()
    {
        if (! isset($this->after_balance)) {
            $this->after_balance = $this->getBeforeBalance() + $this->totalTransactionAmount;
        }

        return $this->after_balance;
    }

    public function getBeforeBalance()
    {
        if (! isset($this->before_balance)) {
            $this->before_balance = $this->request->getMember()->wallet->balance;
        }

        return $this->before_balance;
    }

    protected function hasEnoughBalance()
    {
        return $this->getAfterBalance() >= 0;
    }

    public function getRequestTransactions()
    {
        return $this->requestTransactions;
    }

    protected function getSecretKey()
    {
        return config('game.api.secret_key');
    }

    protected function response(StatusCode $responseCode)
    {
        $this->response = GameResultWebhookService::buildResponse(
            $responseCode,
            0,
            0
        );

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function fails()
    {
        return isset($this->response);
    }

    public static function make(GameResultWebhookRequest $request)
    {
        return new self($request);
    }
}
