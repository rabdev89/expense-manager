<?php

namespace App\Components\Users\Repositories;

use App\Components\Users\Users;
use App\Components\Base\Repositories\BaseRepository;
use App\Components\Users\Exceptions\CreateUsersInvalidArgumentException;
use App\Components\Users\Exceptions\UsersNotFoundException;
use App\Components\Users\Exceptions\UsersPaymentChargingErrorException;
use App\Components\Users\Exceptions\UpdateUsersInvalidArgumentException;
use App\Components\Users\Repositories\UsersRepositoryInterface;
use App\Components\Users\Requests\LoginUserRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection as Support;
use Illuminate\Support\Facades\Auth;

class UsersRepository extends BaseRepository implements UsersRepositoryInterface
{
    /**
     * UsersRepository constructor.
     * @param Users $user
     */
    public function __construct(Users $user)
    {
        parent::__construct($user);
        $this->model = $user;
    }

    /**
     * List all the employees
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function listUsers(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Support
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the Users
     *
     * @param array $params
     * @return Users
     * @throws CreateUsersInvalidArgumentException
     */
    public function createUser(array $params) : Users
    {
        try {
            $data = collect($params)->except('password')->all();

            if (isset($params['password'])) {
                $data['password'] = bcrypt($params['password']);
            }

            return $this->create($data);

        } catch (QueryException $e) {
            throw new CreateUsersInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    /**
     * Update the Users
     *
     * @param array $params
     *
     * @return bool
     * @throws UpdateUsersInvalidArgumentException
     */
    public function updateUser(array $params) : bool
    {
        try {
            $data = collect($params)->except('password')->all();

            if (isset($params['password'])) {
                $data['password'] = bcrypt($params['password']);
            }
            return $this->model->update($params);
        } catch (QueryException $e) {
            throw new UpdateUsersInvalidArgumentException($e);
        }
    }

    /**
     * Find the Users or fail
     *
     * @param int $id
     *
     * @return Users
     * @throws UsersNotFoundException
     */
    public function findUserById(int $id) : Users
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new UsersNotFoundException($e);
        }
    }

    /**
     * Delete a User
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteUser() : bool
    {
        return $this->delete();
    }


    /**
     * @param string $text
     * @return mixed
     */
    public function searchUser(string $text = null) : Collection
    {
        if (is_null($text)) {
            return $this->all();
        }
        return $this->model->searchUser($text)->get();
    }

    /**
     * @param array $roleIds
     */
    public function syncRoles(array $roleIds)
    {
        $this->model->roles()->sync($roleIds);
    }


    /**
     * @return Collection
     */
    public function listRoles(): Collection
    {
        return $this->model->roles()->get();
    }

    /**
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->model->hasRole($roleName);
    }

    /**
     * @param Users $users
     *
     * @return bool
     */
    public function isAuthUser(Users $users): bool
    {
        $isAuthUser = false;
        if (Auth::guard()->user()->id == $users->id) {
            $isAuthUser = true;
        }
        return $isAuthUser;
    }
}
