<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => '1234',
                'name' => 'Live 22',
                'short_name' => 'L22',
                'order' => 1,
                'status' => 1,
            ],
            [
                'code' => '1001',
                'name' => 'ShanKoMee',
                'short_name' => 'SKM',
                'order' => 11,
                'game_list_status' => '0',
                'status' => 1,
            ],
            [
                'code' => '1002',
                'name' => 'Buu Gyi',
                'short_name' => 'BG',
                'order' => 12,
                'status' => 1,

            ],
            [
                'code' => '1003',
                'name' => 'Poker13',
                'short_name' => 'PK',
                'order' => 13,
                'game_list_status' => '0',
                'status' => 1,

            ],
            [
                'code' => '1004',
                'name' => 'BlackJack 21',
                'short_name' => 'BJ',
                'order' => 14,
                'status' => 1,

            ],

        ];

        //Product::insert($data);
        foreach ($data as $obj) {
            Product::create($obj);
        }

    }
}
