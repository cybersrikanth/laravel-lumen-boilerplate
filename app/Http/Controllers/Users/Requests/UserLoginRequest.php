<?php

namespace App\Http\Controllers\Users\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users\User;

class UserLoginRequest extends Controller
{
    public function __construct(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => User::RULES['email'],
                'password' => User::RULES['password']
            ],
            [
                'password.regex' => User::MESSAGES['password.regex']
            ]
        );

        parent::__construct($request);
    }
}
