<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

 

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');


Route::middleware(['auth'])->group(function () {
    Route::get('/user-dashboard', [UserController::class, 'index'])->name('user.index');
});


Route::middleware(['auth',AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [BrandController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand-create', [BrandController::class, 'createBrand'])->name('admin.create.brand');
});
