<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
<<<<<<< HEAD
    return view('landing.index');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/subscriptions', function () {
    return view('subscriptions.index');
=======
    return view('welcome');
>>>>>>> c10f1214337224a0b82730728c6e73ac43aee2cf
});
