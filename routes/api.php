<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/edit/user', [AuthController::class, 'userEdit']);
    Route::get('/delete/user', [AuthController::class, 'userDelete']);

    Route::get('/product', [TransactionController::class, 'getAllProduct']);
    Route::get('/getallproductimage', [TransactionController::class, 'getAllImageProduct']);
    Route::post('/getproductimage', [TransactionController::class, 'getImageProduct']);
    Route::get('/category', [TransactionController::class, 'getAllCategory']);
    Route::get('/categorydetail', [TransactionController::class, 'getAllCategoryDetail']);

    Route::post('/addtransaction', [TransactionController::class, 'addTransaction']);
    Route::post('/gettransaction', [TransactionController::class, 'getAllTransactionOnUser']);
    // Route::post('/gettransactionone', [TransactionController::class, 'getOneTransactionOnIDTrans']);
    Route::post('/getbukti', [TransactionController::class, 'getImageTransaction']);
    Route::post('/edit/rating', [TransactionController::class, 'addTransaction']);
    Route::post('/edit/payment', [TransactionController::class, 'updateBuktiBayar']);
    Route::post('/delete/transaction', [TransactionController::class, 'transactionDelete']);
});
