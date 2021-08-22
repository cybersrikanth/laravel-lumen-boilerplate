<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_agent',
        'ip_address'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
