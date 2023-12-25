<?php

namespace Database\Seeders;

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();
        Product::truncate();
        Category::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            TransactionSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
