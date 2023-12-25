<?php

namespace Database\Seeders;

use App\Category;
use App\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public const COUNT = 30;

    public const PRODUCTS_COUNT = 30;

    public function run(): void
    {
        $products = Product::inRandomOrder()
            ->limit(self::PRODUCTS_COUNT)
            ->get();

        Category::factory()
            ->count(self::COUNT)
            ->hasAttached($products)
            ->create();
    }
}
