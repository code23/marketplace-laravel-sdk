<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Faker\Factory;

class ProductService extends Service
{
    public function list()
    {
        // call
        $response = $this->http->get($this->getPath() . '/products');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // return product list
        return $response;
    }

    /**
     * get the most recently added products
     */
    public function latest($count = 3)
    {
        // init faker instance
        $faker = (new Factory)->create();

        // make products array
        $products = [];

        // fill array with fake products
        for($i = 0; $i < $count; $i++) {
            array_push($products, [
                'name' => $faker->text(30),
                'vendor' => ucwords($faker->words(2, true)),
                'price' => number_format($faker->randomFloat(2, 15, 200), 2),
                'image' => 'https://picsum.photos/900/900?random=' . $faker->randomDigitNotNull,
            ]);
        }

        // return the products
        return $products;
    }
}
