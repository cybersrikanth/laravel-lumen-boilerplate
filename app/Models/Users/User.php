<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Authenticatable
{
    use SoftDeletes;
    use HasFactory;

    const RULES = [
        'email' => ['required', 'email', 'max:191'],
        'password' => ['required', 'min:8', 'max:40', 'regex:/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])/']
    ];

    const MESSAGES = [
        'password.regex' => 'Password should contain atleast one lower case, one upper case and one digit'
    ];

    protected $fillable = [
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    private $current_session;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->current_session = null;
    }

    public function setCurrentSession(Session $session)
    {
        $this->current_session = $session;
    }

    public function getCurrentSession()
    {
        return $this->current_session;
    }

    public function getAuthIdentifierName()
    {
        
    }

    public function getAuthIdentifier()
    {
        
    }

    public function getAuthPassword()
    {
        
    }

    public function getRememberToken()
    {
        
    }

    public function getRememberTokenName()
    {
        
    }

    public function setRememberToken($value)
    {
        
    }
}
