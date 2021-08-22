<?php

use App\Models\Users\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetCurrentSessionTest extends TestCase
{

    use DatabaseTransactions;

    public function testUserGetCurrentSession()
    {
        $user = User::factory()->create();

        $this->useAuth($user);

        $this->request['method'] = 'GET';
        $this->request['uri'] = route('users.session-details');
        $response = $this->fire();

        $this->assertEquals(200, $response->getStatusCode());

        $response->assertJson([
            'email' => $user->email
        ]);
        $response->assertJsonStructure([
            'email',
            'session_initiated_device',
            'session_initiated_ip'
        ]);

        app('DocGen')->make(__FUNCTION__, $this->request, $response);
    }
}
