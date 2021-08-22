<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\Requests\UserLoginRequest;
use App\Http\Controllers\Users\Requests\UserRegisterRequest;
use App\Service\Users\SessionService;
use App\Service\Users\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    private $user_service;
    private $session_service;

    public function __construct(
        UserService $user_service,
        SessionService $session_service
    ) {
        $this->user_service = $user_service;
        $this->session_service = $session_service;
    }

    public function register(UserRegisterRequest $user_register_request)
    {
        $data = $user_register_request->getParams();

        $user = $this->user_service->register($data['email'], $data['password']);

        return $this->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(UserLoginRequest $user_login_request)
    {
        $data = $user_login_request->getParams();
        $request = $user_login_request->request;

        $user = $this->user_service->authenticate($data['email'], $data['password']);
        if (!$user)
            throw new HttpException(401, 'Invalid Email/Password');

        $jwt = $this->session_service->createSessionAndGetJwt($user['id'], $request->ip(), $request->header('User-Agent'));

        return $this->json([
            'user' => $user,
            'access_token' => $jwt
        ]);
    }

    public function sessionDetails(Request $request)
    {
        $user = $request->user();

        return $this->json([
            'email' => $user->email,
            'session_initiated_device' => $user->getCurrentSession()->user_agent,
            'session_initiated_ip' => $user->getCurrentSession()->ip_address
        ]);
    }
}
