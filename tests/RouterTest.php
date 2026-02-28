<?php

declare(strict_types=1);

use Http\Request;
use Http\Response;

/**
 * Pengujian routing: method match, params, 404, dan 405.
 */
final class RouterTest extends TestCase
{
    public function testRouteParamsMatched(): void
    {
        $router = new Router();
        $router->get('/todos/{id}', static function (Request $request, array $params): Response {
            return Response::json(['data' => ['id' => $params['id'] ?? null]]);
        });

        $request = new Request('GET', '/todos/99', '/todos/99');
        $response = $router->dispatch($request);
        $payload = json_decode($response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('99', $payload['data']['id'] ?? null);
    }

    public function testMethodMismatchReturns405(): void
    {
        $router = new Router();
        $router->get('/todos', static fn ($request, $params): Response => Response::json(['data' => []]));

        $request = new Request('POST', '/todos', '/todos');
        $response = $router->dispatch($request);

        $this->assertSame(405, $response->getStatusCode());
        $this->assertTrue(str_contains($response->getHeaders()['Allow'] ?? '', 'GET'));
    }

    public function testUnknownRouteReturns404(): void
    {
        $router = new Router();

        $request = new Request('GET', '/tidak-ada', '/tidak-ada');
        $response = $router->dispatch($request);

        $this->assertSame(404, $response->getStatusCode());
    }
}
