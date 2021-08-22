<?php

use App\Models\Users\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserLoginWithEmailAndPassword()
    {
        $user = User::factory()->create();

        $this->request['method'] = 'POST';
        $this->request['uri'] = route('users.login');
        $this->request['body'] = [
            'email' => $user->email,
            'password' => 'Password@123'
        ];
        $response = $this->fire();

        $this->assertEquals(200, $response->getStatusCode());

        $response->assertJson([
            'user' => [
                'email' => $user->email,
                'is_blacklisted' => 0
            ]
        ]);
        $response->assertJsonStructure([
            'user' => [
                'email',
                'is_blacklisted'
            ],
            'access_token'
        ]);

        $this->seeInDatabase('sessions',[
            'user_id' => $user->id,
        ]);

        app('DocGen')->make(__FUNCTION__, $this->request, $response);
    }

    public function testUserLoginWithEmailAndPasswordPasswordValidation()
    {
        $user = User::factory()->create();

        $this->request['method'] = 'POST';
        $this->request['uri'] = route('users.login');
        $this->request['body'] = [
            'email' => $user->email,
            'password' => 'Password@1234'
        ];
        $response = $this->fire();

        // dd($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());

        $response->assertJson([
            'message' => "Invalid Email/Password"
        ]);
     
        $this->missingFromDatabase('sessions',[
            'user_id' => $user->id,
        ]);
    }
}
