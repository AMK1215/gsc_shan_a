<?php

namespace App\Models\Admin;

use App\Models\GameList;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeamlessEvent extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'message_id',
    //     'product_id',
    //     'request_time',
    //     'raw_data',
    // ];
    protected $fillable = [
        'user_id',
        'game_type_id',
        'product_id',
        'game_list_id',
        'request_time',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'json',
    ];

    public function transactions()
    {
        return $this->hasMany(SeamlessTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game type associated with the event.
     */
    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }

    /**
     * Get the product associated with the event.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the game list associated with the event.
     */
    public function gameList()
    {
        return $this->belongsTo(GameList::class);
    }

    /**
     * Get the transactions for the seamless event.
     */
    public function seamlessTransactions()
    {
        return $this->hasMany(SeamlessTransaction::class);
    }
}
