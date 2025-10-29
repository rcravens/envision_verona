<?php

use App\Http\Controllers\BobsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get( '/', [ HomeController::class, 'dashboard1' ] )->name( 'home.dashboard1' );

Route::get( '/dev/bobs/test', [ BobsController::class, 'test' ] );
