<?php

namespace Database\Factories;

use App\Product;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'quantity'    => $this->faker->numberBetween(1, 10),
            'status'      => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
            'image'       => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
            'seller_id'   => User::inRandomOrder()->first()->id,
        ];
    }
}
