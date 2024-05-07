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
            ['name' => 'sm'],
            ['name' => 'savemore'],
            ['name' => 'puregold'],
            ['name' => 'ace'],
        ];

        DB::table('shops')->insert($shops);

    }
}
