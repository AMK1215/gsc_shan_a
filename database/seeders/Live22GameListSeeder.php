<?php

namespace Database\Seeders;

use App\Models\GameList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class Live22GameListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load the JSON file
        $json = File::get(base_path('game_json/live_22.json'));
        $data = json_decode($json, true);

        // Loop through each game in the JSON file and insert into the database
        foreach ($data['Game'] as $game) {
            GameList::updateOrCreate(
                ['game_id' => $game['GameId']], // Unique game identifier
                [
                    'game_type_id' => 1, // Hardcoded game_type_id
                    'product_id' => 1, // Hardcoded product_id
                    'game_code' => $game['GameCode'],
                    'game_name' => $game['GameName'],
                    'game_type' => $game['GameType'],
                    'image_url' => $game['ImageUrl'],
                    'method' => $game['Method'],
                    'is_h5_support' => $game['IsH5Support'],
                    'maintenance' => $game['Maintenance'],
                    'game_lobby_config' => $game['GameLobbyConfig'],
                    'other_name' => isset($game['OtherName']) ? json_encode($game['OtherName']) : null,
                    'has_demo' => $game['HasDemo'],
                    'sequence' => $game['Sequence'],
                    'game_event' => isset($game['GameEvent']) ? json_encode($game['GameEvent']) : null,
                    'game_provide_code' => $game['GameProvideCode'],
                    'game_provide_name' => $game['GameProvideName'],
                    'is_active' => $game['IsActive'],
                ]
            );
        }
    }
}
