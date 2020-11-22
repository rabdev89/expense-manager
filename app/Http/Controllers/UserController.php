<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Components\Users\Users;
use App\Components\Users\Requests\CreateUsersRequest;
use App\Components\Users\Requests\UpdateUsersRequest;
use App\Components\Users\Repositories\UsersRepository;
use App\Components\Users\Repositories\UsersRepositoryInterface;
use App\Components\Roles\Repositories\RoleRepositoryInterface;
use App\Components\Users\Transformations\UsersTransformable;
use DB;
use Hash;


class UserController extends Controller
{
    use UsersTransformable;

    /**
     * @var UsersRepositoryInterface
     */
    private $userRepo;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(UsersRepositoryInterface $userRepo, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = $this->userRepo->orderBy('id','DESC')->paginate(5);
        $list = $this->userRepo->listUsers('created_at', 'desc');

        $data = $list->map(function (Users $item) {
            return $this->transformUser($item);
        })->all();

        return response()->json($this->userRepo->paginateArrayResults($data, 25), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUsersRequest $request)
    {

        $employee = $this->userRepo->createUser($request->all());

        if ($request->has('role')) {
            $employeeRepo = new UsersRepository($employee);
            $employeeRepo->syncRoles([$request->input('role')]);
        }

        return response()->json('User created successfully', 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepo->findUserById($id);
        $roles = $this->roleRepo->listRoles('created_at', 'desc');
        $isCurrentUser = $this->userRepo->isAuthUser($employee);
        return response()->json([
                'user' => $user,
                'roles' => $roles,
                'isCurrentUser' => $isCurrentUser,
                'selectedIds' => $user->roles()->pluck('role_id')->all()
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProfile($id)
    {
        $user = $this->userRepo->findUserById($id);
        return response()->json(compact('user'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UpdateUsersRequest $request)
    {
        $accessToken = Auth::user()->token();

        $this->update($request, $accessToken->id);
        $user = $this->userRepo->findUserById($accessToken->id);

        $update = new UsersRepository($user);
        $update->updateUser($request->except('_token', '_method'));

        return response()->json(compact('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {

        $user = $this->userRepo->findUserById($id);
        $isCurrentUser = $this->userRepo->isAuthUser($user);

        $uRepo = new UsersRepository($user);
        $uRepo->updateUser($request->except('_token', '_method', 'password'));

        if ($request->has('password') && !empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        if ($request->has('roles') and !$isCurrentUser) {
            $user->roles()->sync($request->input('roles'));
        } elseif (!$isCurrentUser) {
            $user->roles()->detach();
        }

        return response()->json('User updated successfully', 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->findUserById($id);
        $userRepo = new UsersRepository($user);
        $userRepo->deleteUser();
        return response()->json('User deleted successfully', 204);
    }
}
