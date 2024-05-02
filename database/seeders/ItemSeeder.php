<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Define the number of items you want to generate
        $numberOfItems = 50;

        // Generate and insert random data for the specified number of items
        for ($i = 0; $i < $numberOfItems; $i++) {
            $itemType = $faker->randomElement(['meat', 'vegetable', 'fruit', 'drink', 'canned_good', 'others']);
            $name = $this->generateItemName($itemType, $faker);

            $item = [
                'name' => $name,
                'price' => $faker->numberBetween(50, 500),
                'itemType' => $itemType,
                'quantity' => $faker->numberBetween(10, 100),
                'shop' => $faker->randomElement(['sm','savemore','puregold','ace']),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('items')->insert($item);
        }
    }

    /**
     * Generate a name for the item based on its type.
     */
    private function generateItemName($itemType, $faker)
    {
        switch ($itemType) {
            case 'meat':
                return $faker->randomElement(['Pork', 'Beef']);
            case 'vegetable':
                return $faker->randomElement(['Carrot', 'Potato']);
            case 'fruit':
                return $faker->randomElement(['Grapes', 'Banana']);
            case 'drink':
                return $faker->randomElement(['Coke', 'Sprite']);
            case 'canned_good':
                return $faker->randomElement(['Tuna', 'Sardines']);
            default:
                return $faker->word;
        }
    }
}
