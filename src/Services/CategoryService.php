<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Faker\Factory;

class CategoryService extends Service
{
    public function list()
    {
        // init faker instance
        $faker = (new Factory)->create();

        // fake categories
        $categories = [
            [
                'name' => 'Abayas',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Dresses',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Skirts',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Tops',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Pants',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Sportswear',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Swimwear',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Jumpsuits',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Loungewear',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
        ];

        // return them
        return $categories;
    }

    public function category($name)
    {
        // init faker instance
        $faker = (new Factory)->create();

        // fake categories
        $subCategories = [
            [
                'name' => 'Weddings',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Birthdays',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Corporate Events',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Village Fetes',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Grand Opening',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Launch Day',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Reunion',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Space Walks',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
            [
                'name' => 'Easter Egg Hunts',
                'image' => 'https://picsum.photos/600/600?random=' . $faker->randomDigitNotNull
            ],
        ];

        // return them
        return $subCategories;
    }
}
