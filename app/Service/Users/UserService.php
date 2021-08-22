<?php

namespace App\Service\Users;

use App\Repository\Interfaces\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{

    private $user_repository;


    public function __construct(
        UserRepositoryInterface $user_repository
    ) {
        $this->user_repository = $user_repository;
    }

    public function register(string $email, string $password): array
    {
        if ($this->user_repository->has('email', $email))
            throw ValidationException::withMessages(['email' => ['User already exists']]);

        $payload = [
            'email' => $email,
            'password' => $password
        ];
        $user = $this->user_repository->create($payload);

        return $user->toArray();
    }

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->user_repository->get([
            'email' => $email
        ])->first();

        return ($user && Hash::check($password, $user->password)) ? $user->toArray() : null;
    }
}
