<?php

namespace App\Components\Users\Repositories;

use App\Components\Users\Users;
use App\Components\Base\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as Support;

interface UsersRepositoryInterface extends BaseRepositoryInterface
{
    public function listUsers(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Support;

    public function createUser(array $params) : Users;

    public function updateUser(array $params) : bool;

    public function findUserById(int $id) : Users;

    public function deleteUser() : bool;

    public function searchUser(string $text) : Collection;
}
