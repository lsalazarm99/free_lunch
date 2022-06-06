<?php

declare(strict_types=1);

namespace App\Services\WarehouseService\Exceptions;

use RuntimeException;

class RecipeHasNoIngredients extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The recipe of the order has no ingredients registered.');
    }
}
