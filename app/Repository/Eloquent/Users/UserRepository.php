<?php

namespace App\Repository\Eloquent\Users;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Users\UserRepositoryInterface;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $payload): ?Model
    {
        if (isset($payload['password']))
            $payload['password'] = Hash::make($payload['password']);

        return parent::create($payload);
    }
}
