<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('admin.arsip');
    Route::get('/pengaturan/roles', [AdminController::class, 'roles'])->name('admin.roles');
    Route::post('/arsip', [AdminController::class, 'storeArchive'])->name('admin.archives.store');
    Route::patch('/arsip/{dataArsip}', [AdminController::class, 'updateArchive'])->name('admin.archives.update');
    Route::delete('/arsip/{dataArsip}', [AdminController::class, 'destroyArchive'])->name('admin.archives.destroy');
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('admin.roles.store');
    Route::patch('/roles/{role}/permissions', [AdminController::class, 'updateRolePermissions'])->name('admin.roles.permissions.update');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::patch('/users/{user}/password', [AdminController::class, 'updateUserPassword'])->name('admin.users.password.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

Route::middleware('auth')->get('/archives/{dataArsip}/download', [AdminController::class, 'downloadArchive'])
    ->name('archives.download');
Route::middleware('auth')->get('/archives/export', [AdminController::class, 'exportArchives'])
    ->name('archives.export');
Route::middleware('auth')->patch('/archives/{dataArsip}', [ArchiveController::class, 'update'])
    ->name('archives.update');
Route::middleware('auth')->delete('/archives/{dataArsip}', [ArchiveController::class, 'destroy'])
    ->name('archives.destroy');

Route::middleware(['auth', 'role:Pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('dashboard');
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip');
    Route::get('/persetujuan', [PimpinanController::class, 'approvals'])->name('persetujuan');
    Route::patch('/arsip/{dataArsip}/approve', [PimpinanController::class, 'approve'])->name('archives.approve');
    Route::patch('/arsip/{dataArsip}/reject', [PimpinanController::class, 'reject'])->name('archives.reject');
});

Route::middleware(['auth', 'role:Staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip');
    Route::post('/arsip/upload', [StaffController::class, 'upload'])->name('archives.upload');
    Route::delete('/arsip/{dataArsip}', [StaffController::class, 'destroy'])->name('archives.destroy');
});

Route::middleware(['auth', 'role:Auditor'])->prefix('auditor')->name('auditor.')->group(function () {
    Route::get('/dashboard', [AuditorController::class, 'dashboard'])->name('dashboard');
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip');
});
