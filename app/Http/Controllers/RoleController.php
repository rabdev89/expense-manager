<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Permissions\Repositories\PermissionRepositoryInterface;
use App\Components\Roles\Repositories\RoleRepository;
use App\Components\Roles\Repositories\RoleRepositoryInterface;
use App\Components\Roles\Requests\CreateRoleRequest;
use App\Components\Roles\Requests\UpdateRoleRequest;
use App\Components\Roles\Role;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * @var PermissionRepositoryInterface
     */
    private $permissionRepository;

    /**
     * RoleController constructor.
     *
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->roleRepo = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = $this->roleRepo->listRoles('created_at', 'desc')->all();
        return response()->json($this->roleRepo->paginateArrayResults($list, 25), 200);
    }

    /**
     * @param CreateRoleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $data = $request->except('_token', '_method');
        $data['name'] = str_slug($request->input('name'));
        $data['display_name'] = ucwords(str_replace('_', ' ', $request->input('name')));
        $newRepo = $this->roleRepo->createRole($data);


        $role = $this->roleRepo->findRoleById(2);
        $roleRepo = new RoleRepository($role);
        $attachedPermissionsArrayIds = $roleRepo->listPermissions()->pluck('id')->all();

        $newRoleRepo = new RoleRepository($newRepo);
        $newRoleRepo->syncPermissions($attachedPermissionsArrayIds);

        return response()->json('Create role successful!', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->roleRepo->findRoleById($id);

        $roleRepo = new RoleRepository($role);
        $attachedPermissionsArrayIds = $roleRepo->listPermissions()->pluck('id')->all();
        $permissions = $this->permissionRepository->listPermissions(['*'], 'name', 'asc');

        return response()->json(compact(
            'role',
            'permissions',
            'attachedPermissionsArrayIds'
        ), 200);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleRepo->findRoleById($id);
        if ($role) {
            $data = $request->except('_token', '_method');
            $data['name'] = str_slug($request->input('display_name'));
            $data['id'] = $id;
            $this->roleRepo->updateRole($data);

            return response()->json('Update role successful!', 200);
        }
    }
}
