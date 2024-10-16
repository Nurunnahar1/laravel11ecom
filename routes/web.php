<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
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
    
    //category routes
    Route::get('/admin/category-index', [CategoryController::class, 'index'])->name('admin.category.index');
      Route::get('/admin/category-create', [CategoryController::class, 'createCategory'])->name('admin.create.category');
      Route::post('/admin/category-store', [CategoryController::class, 'storeCategory'])->name('admin.category.store');
      Route::get('/admin/category-edit/{slug}', [CategoryController::class, 'editCategory'])->name('admin.category.edit');
      Route::put('/admin/category-update/{slug}', [CategoryController::class, 'updateCategory'])->name('admin.category.update');
      Route::get('/admin/category-delete/{slug}', [CategoryController::class, 'deleteCategory'])->name('admin.category.delete');

});
