<?php

namespace Database\Factories\Users;

use App\Models\Users\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
    	return [
    	    'user_agent' => 'Laravel Unit test',
            'ip_address' => '0.0.0.0' 
    	];
    }
}
