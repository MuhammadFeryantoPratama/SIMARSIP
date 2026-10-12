<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/subscriptions', function () {
    return view('subscriptions.index');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/arsip', function () {
        return view('admin.arsip');
    })->name('admin.arsip');

    Route::get('/pengaturan/roles', function () {
        return view('admin.roles');
    })->name('admin.roles');
});

// Pimpinan Routes
Route::prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('dashboard');

    Route::get('/arsip', function () {
        return view('pimpinan.arsip');
    })->name('arsip');

    Route::get('/persetujuan', function () {
        return view('pimpinan.persetujuan');
    })->name('persetujuan');
});

// Staff Routes
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

    Route::get('/arsip', function () {
        return view('staff.arsip');
    })->name('arsip');
});

// Auditor Routes
Route::prefix('auditor')->name('auditor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('auditor.dashboard');
    })->name('dashboard');

    Route::get('/arsip', function () {
        return view('auditor.arsip');
    })->name('arsip');
});
