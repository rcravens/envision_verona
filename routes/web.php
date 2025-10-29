<?php

use App\Http\Controllers\BobsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get( '/', [
    HomeController::class,
    'dashboard'
] )->name( 'home.dashboard' );

Route::get( '/dev/bobs/test', [
    BobsController::class,
    'test'
] );
