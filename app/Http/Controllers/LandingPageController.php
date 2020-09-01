<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepository;
use App\Url;
use App\UrlRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(UrlRepository $urlRepository)
    {
        $stats = Cache::remember('landing-stats', \DateInterval::createFromDateString('10 minutes'), function () {
            $oldestUrl = Url::oldest()->first();
            return [
                'urls' => Url::count(),
                'requests' => UrlRequest::count(),
                'since' => $oldestUrl ? $oldestUrl->created_at : now()
            ];
        });
        return view('welcome', ['stats' => $stats]);
    }
}
