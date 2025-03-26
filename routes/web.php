<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {

        return redirect()->route('products.index');
    
    } else {

        return redirect()->route('login');
    }
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/sale', [App\Http\Controllers\ProductController::class, 'sale'])->name('products.sale');
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');



});