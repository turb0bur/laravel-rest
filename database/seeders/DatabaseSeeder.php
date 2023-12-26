<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
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
