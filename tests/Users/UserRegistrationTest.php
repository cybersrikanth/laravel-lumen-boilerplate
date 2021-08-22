<?php

use App\Models\Users\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserSignupWithEmailAndPassword()
    {
        $email = $this->faker->unique()->safeEmail();
        $password = 'Password@123';
        $this->request['method'] = 'POST';
        $this->request['uri'] = route('users.register');
        $this->request['body'] = [
            'email' => $email,
            'password' => $password
        ];
        $response = $this->fire();

        $this->assertEquals(201, $response->getStatusCode());

        $response->assertJson([
            'user' => [
                'email' => $email,
            ]
        ]);

        $this->seeInDatabase('users', [
            'email' => $email,
        ]);

        app('DocGen')->make(__FUNCTION__, $this->request, $response);
    }

    public function testUserSignupWithEmailAndPasswordPasswordValidation()
    {
        $email = $this->faker->unique()->safeEmail();
        $password = 'password@123';
        $this->request['method'] = 'POST';
        $this->request['uri'] = route('users.register');
        $this->request['body'] = [
            'email' => $email,
            'password' => $password
        ];
        $response = $this->fire();

        $this->assertEquals(422, $response->getStatusCode());

        $response->assertJson([
            'password' => [
                User::MESSAGES['password.regex']
            ]
        ]);

        $this->missingFromDatabase('users', [
            'email' => $email,
        ]);
    }

    public function testUserSignupWithEmailAndPasswordEmailUniqueValidation()
    {
        $user = User::factory()->create();

        $password = 'Password@123';
        $this->request['method'] = 'POST';
        $this->request['uri'] = route('users.register');
        $this->request['body'] = [
            'email' => $user->email,
            'password' => $password
        ];
        $response = $this->fire();

        // dd($response->getContent());
        $this->assertEquals(422, $response->getStatusCode());

        $response->assertJson([
            'email' => [
                "User already exists"
            ]
        ]);

        $this->seeInDatabase('users', $user->toArray());
    }
}
