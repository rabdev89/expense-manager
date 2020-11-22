<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Components\Users\Repositories\UsersRepository;
use App\Components\Users\Repositories\UsersRepositoryInterface;
use App\Components\Users\Requests\CreateUsersRequest;
use App\Components\Users\Requests\RegisterUsersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/accounts';

    private $usersRepo;

    /**
     * Create a new controller instance.
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->middleware('guest');
        $this->usersRepo = $usersRepository;
    }


     /**
     * Users Register
     *
     * @param  RegisterUsersRequest          $request
     * @return Illuminate\Http\JsonResponse
     */
    public function register(RegisterUsersRequest $request)
    {
        try {
            //$this->userRepo->createUser($request->except('_token', '_method'));

            $user = $this->usersRepo->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);


            //every registered user default role is user
            $userRepo = new UsersRepository($user);
            $userRepo->syncRoles([2]);

            return response()->json([
                'token' => $user->createToken('token')->accessToken,
                'data' => $user
            ], 201);

        } catch (QueryException $e) {
            throw new CreateCustomerInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }
}
