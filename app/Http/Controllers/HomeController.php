<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('home');
    }

    public function getTokens()
    {
        return view('home.personal-tokens');
    }

    public function getClients()
    {
        return view('home.personal-clients');
    }

    public function getAuthorizedClients()
    {
        return view('home.authorized-clients');
    }
}
