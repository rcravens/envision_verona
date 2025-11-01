<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get( '/', [ HomeController::class, 'dashboard' ] )->name( 'home.dashboard' );

Route::view( 'dashboard', 'dashboard' )->middleware( [ 'auth', 'verified' ] )->name( 'dashboard' );

Route::view( 'profile', 'profile' )->middleware( [ 'auth' ] )->name( 'profile' );

require __DIR__ . '/auth.php';
