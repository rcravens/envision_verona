<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopulationModelController extends Controller
{
    public function index( Request $request ): View
    {
        return view( 'models.population' );
    }
}
