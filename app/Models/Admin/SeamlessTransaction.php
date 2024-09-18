<?php

namespace App\Models\Admin;

use App\Enums\TransactionStatus;
use App\Models\Admin\GameType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeamlessTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'seamless_event_id',
        'user_id',
        'product_id',
        'game_type_id',
        'wager_id',
        'seamless_transaction_id',
        'transaction_amount',
        'valid_amount',
        'operator_id',
        'request_date_time',
        'signature',
        'player_id',
        'currency',
        'round_id',
        'bet_id',
        'bet_amount',
        'exchange_rate',
        'game_code',
        'game_type',
        'tran_date_time',
        'auth_token',
        'provider_time_zone',
        'provider_tran_dt',
        'old_balance',
        'new_balance',
        'rollback_type',
        'status',
    ];

    protected $casts = [
        'status' => TransactionStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seamlessEvent()
    {
        return $this->belongsTo(SeamlessEvent::class, 'seamless_event_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the product associated with the transaction.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the game type associated with the transaction.
     */
    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }
}
