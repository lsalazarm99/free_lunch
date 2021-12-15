<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('recipe')
    ->group(function () {
        Route::get('all', [RecipeController::class, 'showAll']);
        Route::get('{recipeId}', [RecipeController::class, 'show']);
    })
;

Route::prefix('order')
    ->group(function () {
        Route::get('search', [OrderController::class, 'search']);
        Route::get('{orderId}', [OrderController::class, 'show']);

        Route::post('random', [OrderController::class, 'createRandom']);

        Route::put('deliver_ingredients/{orderId}', [OrderController::class, 'deliverIngredients']);
    })
;
