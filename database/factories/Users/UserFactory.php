<?php

namespace Database\Factories\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition(): array
    {
    	return [
    	    'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('Password@123')
    	];
    }
}
