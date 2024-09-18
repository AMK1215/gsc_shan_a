<?php

namespace App\Models;

use App\Models\Admin\GameType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameList extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_type_id',
        'product_id',
        'game_id',
        'game_code',
        'game_name',
        'game_type',
        'image_url',
        'method',
        'is_h5_support',
        'maintenance',
        'game_lobby_config',
        'other_name',
        'has_demo',
        'sequence',
        'game_event',
        'game_provide_code',
        'game_provide_name',
        'is_active',
        'click_count',
        'status',
        'hot_status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }

    public function getImgUrlAttribute()
    {
        return asset('/game_logo/'.$this->image);
    }
}
