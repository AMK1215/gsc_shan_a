<?php

namespace Database\Seeders;

use App\Models\Admin\GameType;
use App\Models\GameTypeProduct;
use Illuminate\Database\Seeder;

class GameTypeProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'product_id' => 1,
                'game_type_id' => 1,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 2,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 3,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 4,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 5,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 6,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 7,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 1,
                'game_type_id' => 8,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 2,
                'game_type_id' => 9,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 3,
                'game_type_id' => 10,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
            [
                'product_id' => 4,
                'game_type_id' => 11,
                'image' => 'live33.jpeg',
                'rate' => '1.0000',
            ],
        ];

        GameTypeProduct::insert($data);
    }
}
