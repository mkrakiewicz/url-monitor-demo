<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepository;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(UrlRepository $urlRepository)
    {

//        $user = User::find(1);
//        $urlRepository->getLatestForUserWithAverages($user)->dd();
////        return $user->urls()->withCount(['stats'=>])->get()->dd();
//        return $user->urls()->withCount([
//            'stats as avg_loading_time' => function ($query) {
////                return $builder->select(\DB::raw("AVG('total_loading_time') as total_loading_time"))->groupBy(['laravel_through_key']);
////                return $builder->avg('total_loading_time');
//                return $query->select(\DB::raw('avg(total_loading_time)'));
//            },
//            'stats as avg_redirect_count' => function ($query) {
//                return $query->select(\DB::raw('avg(redirects_count)'));
//            }
//        ])->dd();
        return view('home');
    }
}
