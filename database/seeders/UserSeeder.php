<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const COUNT = 100;

    public function run(): void
    {
        User::factory()
            ->count(self::COUNT)
            ->create();
    }
}
