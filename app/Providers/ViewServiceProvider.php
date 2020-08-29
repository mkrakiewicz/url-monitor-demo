<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function (\Illuminate\Contracts\View\View $view) {
            $view->with('user', Auth::user());
        });

        View::composer('layouts.app', function (\Illuminate\Contracts\View\View $view) {
            $view->with('apiToken', Cookie::get(AuthServiceProvider::API_TOKEN));
        });
    }
}
