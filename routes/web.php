<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('auth')->prefix('admin')
->name('admin.')->group(function(){
    Route::get('category/trashed', [CategoryController::class, 'trashed'])->name('category.trashed');
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.force-delete');
    Route::resource('/category', CategoryController::class);
});


Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('attribute', AttributeController::class);
});

require __DIR__.'/auth.php';