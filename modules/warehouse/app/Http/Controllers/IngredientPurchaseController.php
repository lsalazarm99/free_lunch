<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\IngredientPurchaseResource;
use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class IngredientPurchaseController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        $request->validate(
            [
                'ingredient_id' => ['nullable', 'integer', Rule::exists(Ingredient::class, 'id')],
                'date_from' => ['nullable', 'date', 'before_or_equal::date_to', 'before_or_equal:now'],
                'date_to' => ['nullable', 'date', 'after_or_equal::date_from', 'before_or_equal:now'],
                'max_items_number' => ['nullable', 'int', 'between:1,15'],
            ],
        );

        $ingredientPurchasesQuery = IngredientPurchase::query()
            ->with('ingredient')
            ->orderBy('created_at', 'desc')
        ;

        if ($request->filled('ingredient_id')) {
            $ingredientPurchasesQuery->where('ingredient_id', '=', $request->input('ingredient_id'));
        }

        if ($request->filled('date_from')) {
            $ingredientPurchasesQuery->whereDate('created_at', '>=', Carbon::make($request->input('date_from')));
        }

        if ($request->filled('date_to')) {
            $ingredientPurchasesQuery->whereDate('created_at', '<=', Carbon::make($request->input('date_to')));
        }

        if ($request->filled('max_items_number')) {
            $ingredientPurchases = $ingredientPurchasesQuery->paginate($request->input('max_items_number'));
        } else {
            $ingredientPurchases = $ingredientPurchasesQuery->paginate(15);
        }

        $ingredientPurchases->withQueryString();

        return IngredientPurchaseResource::collection($ingredientPurchases);
    }
}
