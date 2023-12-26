<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public const COUNT = 1000;

    public function run(): void
    {
        Transaction::factory()
            ->count(self::COUNT)
            ->create();
    }
}
