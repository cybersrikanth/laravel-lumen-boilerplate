<?php

namespace App\Repository\Eloquent\Users;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Users\SessionRepositoryInterface;
use App\Models\Users\Session;
use Illuminate\Database\Eloquent\Model;

class SessionRepository extends BaseRepository implements SessionRepositoryInterface{

    protected $model;

    public function __construct(Session $model)
    {
        $this->model = $model;   
    }

    public function create(array $payload): ?Model
    {
        if($payload['user_agent'])
            $payload['user_agent'] = substr($payload['user_agent'], 0, 100);
        
        return parent::create($payload);
    }
}