<?php

namespace Database\Factories;

use App\Seller;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $seller = Seller::has('products')->inRandomOrder()->first();
        $buyer = User::inRandomOrder()->firstWhere('id', '<>', $seller->id);

        return [
            'quantity'   => $this->faker->numberBetween(1, 3),
            'buyer_id'   => $buyer->id,
            'product_id' => $seller->products->random()->id,
        ];
    }
}
