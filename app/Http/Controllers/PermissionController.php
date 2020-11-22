<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Permissions\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     */
    private $permRepo;

    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permRepo = $permissionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = $this->permRepo->listPermissions();

        $permissions = $this->permRepo->paginateArrayResults($list->all());
        return response()->json(compact('permissions'), 200);
        //return view('admin.permissions.list', compact('permissions'));
    }
}
