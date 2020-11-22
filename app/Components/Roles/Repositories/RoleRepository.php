<?php
namespace App\Components\Roles\Repositories;

use App\Components\Base\Repositories\BaseRepository;
use App\Components\Permissions\Permission;
use App\Components\Permissions\Repositories\PermissionRepository;
use App\Components\Roles\Exceptions\CreateRoleErrorException;
use App\Components\Roles\Exceptions\DeleteRoleErrorException;
use App\Components\Roles\Exceptions\RoleNotFoundErrorException;
use App\Components\Roles\Exceptions\UpdateRoleErrorException;
use App\Components\Roles\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @var Role
     */
    protected $model;
    /**
     * RoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct($role);
        $this->model = $role;
    }
    /**
     * List all Roles
     *
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listRoles(string $order = 'id', string $sort = 'desc') : Collection
    {
        return $this->all(['*'], $order, $sort);
    }
    /**
     * @param array $data
     * @return Role
     * @throws CreateRoleErrorException
     */
    public function createRole(array $data) : Role
    {
        try {
            $role = new Role($data);
            $role->save();
            return $role;
        } catch (QueryException $e) {
            throw new CreateRoleErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Role
     * @throws RoleNotFoundErrorException
     */
    public function findRoleById(int $id) : Role
    {
        try {
            return $this->findOneOrFail($id);
        } catch (QueryException $e) {
            throw new RoleNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws UpdateRoleErrorException
     */
    public function updateRole(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateRoleErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws DeleteRoleErrorException
     */
    public function deleteRoleById() : bool
    {
        try {
            return $this->delete();
        } catch (QueryException $e) {
            throw new DeleteRoleErrorException($e);
        }
    }

    /**
     * @param Permission $permission
     */
    public function attachToPermission(Permission $permission)
    {
        $this->model->attachPermission($permission);
    }

    /**
     * @param Permission ...$permissions
     */
    public function attachToPermissions(... $permissions)
    {
        $this->model->attachPermissions($permissions);
    }

    /**
     * @param array $ids
     */
    public function syncPermissions(array $ids)
    {
        $this->model->syncPermissions($ids);
    }

    /**
     * @return Collection
     */
    public function listPermissions() : Collection
    {
        return $this->model->permissions()->get();
    }
}
