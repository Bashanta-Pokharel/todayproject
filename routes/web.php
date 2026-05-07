<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| DASHBOARD (FIXED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ALL FIXED + CLEAN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
->prefix('admin')
->name('admin.')
->group(function () {

    /*
    |-----------------------
    | PRODUCT
    |-----------------------
    */

    Route::resource('product', ProductController::class);

    Route::get('product/trashed', [ProductController::class, 'trashed'])
        ->name('product.trashed');

    Route::post('product/{id}/restore', [ProductController::class, 'restore'])
        ->name('product.restore');

    Route::delete('product/{id}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('product.force-delete');

    // IMAGE (FIXED - THIS WAS MISSING EARLIER)
    Route::post('product/{id}/image/add', [ProductController::class, 'addImage'])
        ->name('product.image.add');

    Route::delete('product/image/{id}', [ProductController::class, 'deleteImage'])
        ->name('product.image.delete');

    /*
    |-----------------------
    | ATTRIBUTE (PRODUCT)
    |-----------------------
    */

    Route::post('product/{product}/attribute', [ProductController::class, 'addAttribute'])
        ->name('product.attribute.add');

    Route::delete('product/{product}/attribute/{attribute}', [ProductController::class, 'deleteAttribute'])
        ->name('product.attribute.delete');

    /*
    |-----------------------
    | CATEGORY
    |-----------------------
    */

    Route::resource('category', CategoryController::class);

    Route::get('category/trashed', [CategoryController::class, 'trashed'])
        ->name('category.trashed');

    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])
        ->name('category.restore');

    Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])
        ->name('category.force-delete');

    /*
    |-----------------------
    | ATTRIBUTE
    |-----------------------
    */

    Route::resource('attribute', AttributeController::class);

    Route::get('attribute/trashed', [AttributeController::class, 'trashed'])
        ->name('attribute.trashed');

    Route::post('attribute/{id}/restore', [AttributeController::class, 'restore'])
        ->name('attribute.restore');

    Route::delete('attribute/{id}/force-delete', [AttributeController::class, 'forceDelete'])
        ->name('attribute.force-delete');
});

Route::name('frontend.')->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('index');
    Route::get('/listing/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'listing'])->name('listing');
    Route::get('/details/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'details'])->name('details');
});

/*
|--------------------------------------------------------------------------
| AUTH FILES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';