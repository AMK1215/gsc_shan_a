<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $media = [
            ['name' => 'viber', 'image' => 'viber.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'telegram', 'image' => 'telegram.png', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('media_types')->insert($media);
    }
}
