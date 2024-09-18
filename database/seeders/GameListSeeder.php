<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            [
                'game_type_id' => 1,
                'product_id' => 1,
                'game_id' => 30000,
                'game_code' => '30000',
                'game_name' => 'Candy Bomb',
                'game_type' => 1,
                'image_url' => 'https://l22gth.l22play.com/thumbs/web/30000.png',
                'method' => 'Slots',
                'is_h5_support' => true,
                'maintenance' => '1|2|3|4|5|6',
                'game_lobby_config' => '0|0|1|1692181611',
                'other_name' => json_encode(['zh-cn|糖果工房']),
                'has_demo' => true,
                'sequence' => 1,
                'game_event' => json_encode([]),
                'game_provide_code' => 'Live22',
                'game_provide_name' => 'Live22',
                'is_active' => true,
                'click_count' => 0,
                'status' => 1,
                'hot_status' => 0,
            ],
            // Repeat for each game...
        ];

        DB::table('game_lists')->insert($games);
    }
}
