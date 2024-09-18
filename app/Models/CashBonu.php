<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBonu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tran_id',
        'bonus_id',
        'bonus_name',
        'result',
        'currency',
        'exchange_rate',
        'payout',
        'player_id',
        'tran_date_time',
        'provider_time_zone',
        'provider_tran_dt',
        'operator_id',
        'request_date_time',
        'signature',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
