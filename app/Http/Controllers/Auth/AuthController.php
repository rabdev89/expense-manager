<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Components\Users\Users;
//use Tymon\JWTAuth\JWTAuth;
//use Tymon\JWTAuth\Exceptions\JWTException;
use App\Components\Users\Requests\CreateUsersRequest;
use App\Components\Users\Requests\RegisterUsersRequest;
use App\Components\Users\Requests\LoginUserRequest;
use App\Components\Users\Repositories\UsersRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection as Support;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @var UsersRepositoryInterface
     */
    protected $userRepo;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(UsersRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * User login
     *
     * @param  LoginUserRequest             $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postLogin(LoginUserRequest $request)
    {
        try {
            if (!(Auth::attempt($request->only('email', 'password')))) {
                return response()->json(['error' => 'Invalid email or password!'], 401);
            }
            $user = Auth::user();
            return response()->json([
                'data' => $request->user(),
                'token' =>  $user->createToken('token')->accessToken
            ], 200);

        } catch(JWTException $e) {
            return response()->json(['error' => 'Invalid email or password!'], 401);
        }

    }


    /**
     * Logout user.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();

    	\DB::table('oauth_access_tokens')
    		->where('user_id', $accessToken->id)
    		->update(['revoked' => true]);

    	$accessToken->revoke();

        return response()->json([], 204);
    }

    /**
     * Login user data.
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return response()->json($user, 201);
        }
    }
}
