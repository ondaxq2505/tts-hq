<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends BaseModel
{
    protected $fillable = [
        'email',
        'code',
        'status',
        'expires_at',
    ];

    public function user()
    {
        //
    }
}
