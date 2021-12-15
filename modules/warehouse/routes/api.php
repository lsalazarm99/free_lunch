<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

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

Route::get('ingredient/all', [IngredientController::class, 'showAll']);

Route::post('order', [OrderController::class, 'storeOrder']);
