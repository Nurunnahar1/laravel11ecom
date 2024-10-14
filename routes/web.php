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
    Route::post('/admin/brand-store', [BrandController::class, 'storeBrand'])->name('admin.brand.store');
    Route::get('/admin/brand-edit/{slug}', [BrandController::class, 'editBrand'])->name('admin.brand.edit');
    Route::put('/admin/brand-update/{slug}', [BrandController::class, 'updateBrand'])->name('admin.brand.update');
    Route::get('/admin/brand-delete/{slug}', [BrandController::class, 'deleteBrand'])->name('admin.brand.delete');
});
