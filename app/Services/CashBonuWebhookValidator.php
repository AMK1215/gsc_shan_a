<?php

namespace App\Services;

use App\Enums\StatusCode;
use App\Http\Requests\CashBonuRequest;
use App\Models\Admin\SeamlessTransaction;
use Illuminate\Support\Facades\Log;

class CashBonuWebhookValidator
{
    protected ?SeamlessTransaction $existingTransaction = null;

    protected float $totalTransactionAmount = 0;

    protected float $before_balance = 0;

    protected float $after_balance = 0;

    protected array $response = [];

    protected array $requestTransactions = [];

    protected function __construct(protected CashBonuRequest $request) {}

    public function validate()
    {
        if (! $this->isValidSignature()) {
            return $this->response(StatusCode::InvalidSignature);
        }

        if (! $this->request->getMember()) {
            return $this->response(StatusCode::InvalidPlayer);
        }

        $this->processTransaction($this->request->all());

        if (! $this->hasEnoughBalance()) {
            return $this->response(StatusCode::InsufficientBalance);
        }

        return $this;
    }

    protected function processTransaction(array $transaction)
    {
        $requestTransaction = new CashBonuRequestTransaction(
            $transaction['Status'] ?? 1,
            null, // ProductID is null since it's not promotion in CashBonus
            null, // GameCode is null for CashBonus
            null, // GameType is null for CashBonus
            $transaction['TranId'], // Using TranId as BetId equivalent
            null, // TransactionID is null since it's not used here
            null, // WagerID is null since it's not used here
            null, // BetAmount is null for CashBonus
            $transaction['Payout'], // PayoutAmount is used as TransactionAmount
            $transaction['Payout']  // ValidBetAmount is the same as Payout
        );

        $this->requestTransactions[] = $requestTransaction;

        if ($requestTransaction->TransactionID && ! $this->isNewTransaction($requestTransaction)) {
            return $this->response(StatusCode::DuplicateTransaction);
        }

        $this->totalTransactionAmount += $requestTransaction->TransactionAmount;
    }

    protected function isValidSignature()
    {
        $method = $this->request->getMethodName();
        $tran_id = $this->request->getTranId();
        $requestTime = $this->request->getRequestDateTime();
        $operatorCode = $this->request->getOperatorId();
        $secretKey = $this->getSecretKey();
        $playerId = $this->request->getPlayerId();

        Log::info('Generating signature', [
            'method' => $method,
            'tran_id' => $tran_id,
            'requestTime' => $requestTime,
            'operatorCode' => $operatorCode,
            'secretKey' => $secretKey,
            'playerId' => $playerId,
        ]);

        $signature = md5($method.$tran_id.$requestTime.$operatorCode.$secretKey.$playerId);

        Log::info('Generated signature', ['signature' => $signature]);

        return $this->request->getSignature() === $signature;
    }

    protected function isNewTransaction(CashBonuRequestTransaction $transaction)
    {
        return ! $this->getExistingTransaction($transaction);
    }

    public function getExistingTransaction(CashBonuRequestTransaction $transaction)
    {
        if (! isset($this->existingTransaction)) {
            $this->existingTransaction = SeamlessTransaction::where('bet_id', $transaction->BetId)->first();
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
        $this->response = CashBonuWebhookService::buildResponse(
            $responseCode,
            $this->request->getMember() ? $this->getBeforeBalance() : 0,
            $this->request->getMember() ? $this->getAfterBalance() : 0
        );

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function fails()
    {
        $fails = isset($this->response) && ! empty($this->response);
        Log::info('Checking if validation fails', ['fails' => $fails]);

        return $fails;
    }

    public static function make(CashBonuRequest $request)
    {
        return new self($request);
    }
}
