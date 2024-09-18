<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'account_name' => 'Test',
                'account_number' => '12343453',
                'user_id' => 4,
                'payment_type_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_name' => 'Tes2t',
                'account_number' => '1234345334',
                'user_id' => 4,
                'payment_type_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('banks')->insert($banks);

    }
}
