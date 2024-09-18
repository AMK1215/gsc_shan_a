<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'operator_id',            // OperatorId
        'request_date_time',      // RequestDateTime
        'signature',              // Signature
        'player_id',              // PlayerId
        'currency',               // Currency
        'result_id',              // ResultId
        'bet_id',                 // BetId
        'round_id',               // RoundId
        'game_code',              // GameCode
        'game_type',              // GameType
        'game_name',              // GameName
        'result_type',            // ResultType
        'bet_amount',             // BetAmount
        'valid_bet_amount',       // ValidBetAmount
        'payout',                 // Payout
        'win_lose',               // WinLose
        'exchange_rate',          // ExchangeRate
        'tran_date_time',         // TranDateTime
        'provider_time_zone',     // ProviderTimeZone
        'provider_tran_dt',       // ProviderTranDt
        'round_type',             // RoundType
    ];
}
