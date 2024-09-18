<?php

namespace Database\Seeders;

use App\Models\Admin\GameType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Slots',
                'name_mm' => 'စလော့',
                'code' => '1',
                'order' => '1',
                'status' => 1,
                'img' => 'live22.png',
            ],
            [
                'name' => 'Arcade',
                'name_mm' => 'Arcade',
                'code' => '2',
                'order' => '2',
                'status' => 0,
                'img' => 'live_casino.png',
            ],
            [
                'name' => 'Table',
                'name_mm' => 'Table',
                'code' => '3',
                'order' => '3',
                'status' => 0,
                'img' => 'sportbook.png',
            ],
            [
                'name' => 'Event',
                'name_mm' => 'Event',
                'code' => '4',
                'order' => '4',
                'status' => 0,
                'img' => 'fishing.png',
            ],
            [
                'name' => 'MiniGame',
                'name_mm' => 'MiniGame',
                'code' => '5',
                'order' => '5',
                'status' => 0,
                'img' => 'other.png',
            ],

            [
                'name' => 'FishArcade',
                'name_mm' => 'FishArcade',
                'code' => '6',
                'order' => '6',
                'status' => 0,
                'img' => 'other.png',
            ],

            [
                'name' => 'CashBonus',
                'name_mm' => 'CashBonus',
                'code' => '101',
                'order' => '7',
                'status' => 0,
                'img' => 'other.png',
            ],
            [
                'name' => 'Other',
                'name_mm' => 'အခြား',
                'code' => '0',
                'order' => '8',
                'status' => 0,
                'img' => 'others.png',
            ],
            [
                'name' => 'ShanKoMee',
                'name_mm' => 'ရှမ်းကိုးမီး',
                'code' => '1001',
                'order' => '9',
                'status' => 1,
                'img' => 'shan_1.jfif',
            ],
            [
                'name' => 'Buu Gyi',
                'name_mm' => 'ဘူကြီး',
                'code' => '1002',
                'order' => '10',
                'status' => 1,
                'img' => 'bugyi.jfif',
            ],
            [
                'name' => 'Poker13',
                'name_mm' => 'ပိုကာ',
                'code' => '1003',
                'order' => '11',
                'status' => 1,
                'img' => 'poker_1.jfif',
            ],
            [
                'name' => 'BlackJack 21',
                'name_mm' => '၂၁',
                'code' => '1004',
                'order' => '12',
                'status' => 1,
                'img' => '21.jfif',
            ],
        ];

        GameType::insert($data);
    }
}
