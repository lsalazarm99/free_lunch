<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Console\Kernel
 * @covers \App\Exceptions\Handler
 * @covers \App\Http\Kernel
 * @covers \App\Providers\AppServiceProvider
 * @covers \App\Providers\RouteServiceProvider
 *
 * @internal
 */
final class AppTest extends TestCase
{
    use RefreshDatabase;

    public function testAppInitialize(): void
    {
        $response = $this->get('/');

        $response->assertNotFound();
    }
}
