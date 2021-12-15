<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('recipe')
    ->group(function () {
        Route::get('all', [RecipeController::class, 'all']);
        Route::get('{recipeId}', [RecipeController::class, 'show']);
    })
;
