<?php

namespace Database\Seeders;

use App\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public const COUNT = 1000;

    public function run(): void
    {
        Product::factory()
            ->count(self::COUNT)
            ->create();
    }
}
