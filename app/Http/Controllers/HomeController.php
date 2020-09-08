<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRequestRepository;
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
    public function index(Request $request, UrlRequestRepository $urlRequestRepository)
    {
        return view('home', ['lastRequestId' => $urlRequestRepository->getLastRequest($request->user())->id]);
    }
}
