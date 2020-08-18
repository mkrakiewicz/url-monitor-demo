<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AuthServiceProvider;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as logoutActions;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @param User $user
     */
    protected function authenticated(Request $request, User $user)
    {
        $token = $user->createToken('personal');
        Session::put(AuthServiceProvider::API_TOKEN, $token->plainTextToken);
    }

    public function logout(Request $request)
    {
        /** @var User $userBeforeLogout */
        $userBeforeLogout = $request->user();
        $logoutResult = $this->logoutActions($request);
        $userBeforeLogout->tokens()->delete();
        return $logoutResult;
    }
}
