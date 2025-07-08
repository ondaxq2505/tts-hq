<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    protected $fillable = [
        'shop_id',
        'title',
        'name',
        'parent_id',
        'order',
        'description',
        'guard_name',
        'type_information',
        'shop_access',
        'shop_expect',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
