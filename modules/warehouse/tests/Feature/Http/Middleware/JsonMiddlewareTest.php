<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\JsonMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @covers \App\Http\Middleware\JsonMiddleware
 *
 * @internal
 */
class JsonMiddlewareTest extends TestCase
{
    public function testHeaderIsSet(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/xml');

        (new JsonMiddleware())->handle($request, function (Request $request) {
            $this->assertEquals('application/json', $request->header('Accept'));
        });
    }
}
