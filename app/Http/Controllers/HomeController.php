<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;

class HomeController extends Controller
{
    /**
     * Show the home.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $token = $request->session()->token();
        $token = csrf_token();
        return view('home');
    }
}
