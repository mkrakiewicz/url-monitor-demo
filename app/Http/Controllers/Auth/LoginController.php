<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AuthServiceProvider;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Cookie\CookieJar;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        logout as originalLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * Create a new controller instance.
     *
     * @param CookieJar $cookieJar
     */
    public function __construct(CookieJar $cookieJar)
    {
        $this->middleware('guest')->except('logout');
        $this->cookieJar = $cookieJar;
    }

    /**
     * @param Request $request
     * @param User $user
     */
    protected function authenticated(Request $request, User $user)
    {
        $token = $user->createToken('personal');
        $cookie = $this->cookieJar->forever(AuthServiceProvider::API_TOKEN, $token->plainTextToken);
        $this->cookieJar->queue($cookie);
    }

    public function logout(Request $request)
    {
        /** @var User $userBeforeLogout */
        $userBeforeLogout = $request->user();
        $logoutResult = $this->originalLogout($request);
        $userBeforeLogout->tokens()->delete();
        $cookie = $this->cookieJar->forget(AuthServiceProvider::API_TOKEN);
        $this->cookieJar->queue($cookie);
        return $logoutResult;
    }
}
