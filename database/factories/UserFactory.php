<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'is_verified' => $verified = $this->faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
            'verification_token' =>  $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
            'is_admin' => $this->faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
        ];
    }
}
