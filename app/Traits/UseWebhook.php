<?php

namespace App\Traits;

use App\Enums\TransactionName;
use App\Models\User;
use App\Services\WalletService;

trait UseWebhook
{
    public function processTransfer(User $from, User $to, TransactionName $transactionName, float $amount, int $rate)
    {
        // TODO: ask: what if operator doesn't want to pay bonus
        app(WalletService::class)
            ->transfer(
                $from,
                $to,
                abs($amount),
                $transactionName,
                $rate
            );
    }
}
