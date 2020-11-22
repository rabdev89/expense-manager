<?php

namespace App\Components\Users\Transformations;

use App\Components\Users\Users;

trait UsersTransformable
{
    protected function transformUser(Users $user)
    {
        $prop = new Users;
        $prop->id = (int) $user->id;
        $prop->name = $user->name;
        $prop->email = $user->email;
        foreach ($user->roles as $roles) {
            $prop->role = $roles->display_name;
        }
        $prop->created_at = $user->created_at;
        $prop->status = (int) $user->status;

        return $prop;
    }
}
