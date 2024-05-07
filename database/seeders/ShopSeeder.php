<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $shops = [
            ['shopName' => 'sm'],
            ['shopName' => 'savemore'],
            ['shopName' => 'puregold'],
            ['shopName' => 'ace'],
        ];

        DB::table('shops')->insert($shops);

    }
}
