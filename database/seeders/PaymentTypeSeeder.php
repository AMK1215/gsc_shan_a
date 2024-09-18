<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'KBZPay',
                'image' => 'KBZPay.png',
            ],
            [
                'name' => 'WavePay',
                'image' => 'WavePay.png',
            ],
            [
                'name' => 'AYAPay',
                'image' => 'AYAPay.png',
            ],
            [
                'name' => 'AYABanking',
                'image' => 'AYABanking.png',
            ],
            [
                'name' => 'KBZBanking',
                'image' => 'KBZBanking.png',
            ],
            [
                'name' => 'CBBanking',
                'image' => 'CBBanking.png',
            ],
            [
                'name' => 'CBPay',
                'image' => 'CBPay.png',
            ],
            [
                'name' => 'YomaBanking',
                'image' => 'YomaBanking.png',
            ],
            [
                'name' => 'MABanking',
                'image' => 'MABBanking.png',
            ],
            [
                'name' => 'UABPay',
                'image' => 'UABPay.png',
            ],
        ];

        DB::table('payment_types')->insert($types);
    }
}
