<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Redirect;
use Illuminate\Session\Middleware\StartSession;

Route::middleware([Redirect::class])->group(function () {
    Route::get('/', function () {
        return view('auth');
    })->name('auth');

    Route::get('/app', function () {
        return view('app');
    })->name('app');
});

