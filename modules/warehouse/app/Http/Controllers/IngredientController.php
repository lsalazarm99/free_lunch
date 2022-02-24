<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IngredientController extends Controller
{
    /**
     * @return AnonymousResourceCollection<IngredientResource>
     */
    public function showAll(): AnonymousResourceCollection
    {
        $ingredients = Ingredient::all();

        return IngredientResource::collection($ingredients);
    }
}
