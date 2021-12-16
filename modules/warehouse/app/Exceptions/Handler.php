<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (HttpException $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        });
    }
}
