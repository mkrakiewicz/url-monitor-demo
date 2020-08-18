<?php

namespace App\Http\Middleware;

use App\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

//    /**
//     * Determine if the user is logged in to any of the given guards.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param array $guards
//     *
//     * @throws \Illuminate\Auth\AuthenticationException
//     */
//    protected function authenticate($request, array $guards)
//    {
//        if (empty($guards)) {
//            $guards = [null];
//        }
//
//        foreach ($guards as $guard) {
//            if ($this->auth->guard($guard)->check()) {
//
//                /** @var User $authenticatable */
//                $authenticatable = $this->auth->guard($guard)->user();
//                $authenticatable->currentAccessToken()
//                $authenticatable->save();
////                dd($authenticatable);
//
//                return $this->auth->shouldUse($guard);
//            }
//        }
//
//        $this->unauthenticated($request, $guards);
//    }
}
