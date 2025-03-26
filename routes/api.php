
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
    Route::get('/', [SalesController::class, 'index']); // すべての販売データを取得
    Route::post('/', [SalesController::class, 'store']); // 新しい販売データを作成
    Route::get('/{id}', [SalesController::class, 'show']); // 特定の販売データを取得
    Route::put('/{id}', [SalesController::class, 'update']); // 販売データを更新
    Route::delete('/{id}', [SalesController::class, 'destroy']); // 販売データを削除
});

Route::delete('/products/{id}', [ProductController::class, 'destroy']); 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
