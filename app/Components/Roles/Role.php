<?php

namespace App\Components\Roles;

use Laratrust\Models\LaratrustRole;
use App\Components\Users\Users;

class Role extends LaratrustRole
{
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public function users()
    {
        return $this->belongsTo(Users::class);
    }
}
