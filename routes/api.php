
<?php

use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('sales')->group(function () {
    Route::get('/', [SalesController::class, 'index']); 
    Route::post('/', [SalesController::class, 'store']); 
    Route::get('/{id}', [SalesController::class, 'show']); 
    Route::put('/{id}', [SalesController::class, 'update']); 
    Route::delete('/{id}', [SalesController::class, 'destroy']);
});

Route::delete('/products/{id}', [ProductController::class, 'destroy']); 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
