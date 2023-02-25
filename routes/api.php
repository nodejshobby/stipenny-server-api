<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\StipendController;
use App\Http\Controllers\V1\BeneficiaryController;

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

Route::prefix('v1')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify'); 
    Route::get('/email/verify/resend',[AuthController::class, 'resend'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::post('/password/forgot', [AuthController::class, 'forgot']);
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.reset');


    Route::post('/stipends/create', [StipendController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/beneficiaries/create/{stipend}', [BeneficiaryController::class, 'store'])->middleware('auth:sanctum');
});

