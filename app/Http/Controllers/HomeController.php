<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function dashboard1( Request $request ): View
    {
        return view( 'home.dashboard1' );
    }
}
