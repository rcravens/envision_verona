<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Models\PopulationModelController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Reports\ReportController;
use Illuminate\Support\Facades\Route;

Route::get( '/', [ HomeController::class, 'dashboard' ] )->name( 'home' );

// Projects
Route::prefix( 'projects' )->name( 'projects.' )->group( function () {
    Route::get( '/', [ ProjectController::class, 'index' ] )->name( 'index' );
    Route::get( '/{slug}', [ ProjectController::class, 'analysis' ] )->name( 'analysis' );
} );

// Reports
Route::prefix( 'reports' )->name( 'reports.' )->group( function () {
    Route::get( '/', [ ReportController::class, 'index' ] )->name( 'index' );
    Route::get( '/{slug}', [ ReportController::class, 'analysis' ] )->name( 'analysis' );
} );

// Models
Route::prefix( 'models' )->name( 'models.' )->group( function () {
    Route::get( '/population', [ PopulationModelController::class, 'index' ] )->name( 'population' );
} );

//Route::view( 'dashboard', 'dashboard' )->middleware( [ 'auth', 'verified' ] )->name( 'dashboard' );

//Route::view( 'profile', 'profile' )->middleware( [ 'auth' ] )->name( 'profile' );

//require __DIR__ . '/auth.php';
