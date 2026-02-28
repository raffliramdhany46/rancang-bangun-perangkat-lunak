<?php

declare(strict_types=1);

use Http\Request;
use Http\Response;

/**
 * Router HTTP sederhana dengan dukungan method, parameter path, dan middleware.
 */
final class Router
{
    /** @var array<string, array<int, array<string, mixed>>> */
    private array $routes = [];

    /** @var array<int, callable(Request, callable(Request): Response, array<string, string>): Response> */
    private array $globalMiddleware = [];

    /**
     * Menambahkan middleware global.
     */
    public function middleware(callable $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    /**
     * Registrasi route GET.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    public function get(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Registrasi route POST.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    public function post(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Registrasi route PUT.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    public function put(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    /**
     * Registrasi route PATCH.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    public function patch(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('PATCH', $path, $handler, $middleware);
    }

    /**
     * Registrasi route DELETE.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    public function delete(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    /**
     * Menjalankan dispatch request ke handler route.
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $path = $this->normalizePath($request->path());
        $allowedMethods = [];

        foreach ($this->routes as $routeMethod => $routes) {
            foreach ($routes as $route) {
                if (!preg_match($route['regex'], $path, $matches)) {
                    continue;
                }

                if ($routeMethod !== $method) {
                    $allowedMethods[] = $routeMethod;
                    continue;
                }

                $params = $this->extractParams($matches, $route['paramNames']);

                return $this->runRoute($request, $params, $route['handler'], $route['middleware']);
            }
        }

        if ($allowedMethods !== []) {
            $allowedMethods = array_values(array_unique($allowedMethods));
            sort($allowedMethods);
            $headers = ['Allow' => implode(', ', $allowedMethods)];

            if ($request->wantsJson()) {
                return Response::json([
                    'error' => [
                        'message' => 'Method tidak diizinkan untuk endpoint ini.',
                        'details' => ['allowed' => $allowedMethods],
                    ],
                ], 405, $headers);
            }

            return Response::html('<h1>405 Method Not Allowed</h1>', 405, $headers);
        }

        if ($request->wantsJson()) {
            return Response::json([
                'error' => [
                    'message' => 'Endpoint tidak ditemukan.',
                    'details' => [],
                ],
            ], 404);
        }

        return Response::html('<h1>404 Not Found</h1>', 404);
    }

    /**
     * Menambahkan definisi route ke koleksi internal.
     *
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $middleware
     */
    private function addRoute(string $method, string $path, callable $handler, array $middleware = []): void
    {
        $normalizedPath = $this->normalizePath($path);
        $compiled = $this->compilePattern($normalizedPath);

        $this->routes[$method][] = [
            'path' => $normalizedPath,
            'regex' => $compiled['regex'],
            'paramNames' => $compiled['paramNames'],
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    /**
     * Mengubah path dengan placeholder ke regex dan daftar nama parameter.
     *
     * Contoh: /todos/{id} menjadi regex dengan grup named `id`.
     *
     * @return array{regex: string, paramNames: array<int, string>}
     */
    private function compilePattern(string $path): array
    {
        $paramNames = [];

        $regex = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static function (array $matches) use (&$paramNames): string {
                $paramNames[] = $matches[1];

                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $path
        );

        return [
            'regex' => '#^' . $regex . '$#',
            'paramNames' => $paramNames,
        ];
    }

    /**
     * @param array<string, string> $matches
     * @param array<int, string> $paramNames
     * @return array<string, string>
     */
    private function extractParams(array $matches, array $paramNames): array
    {
        $params = [];

        foreach ($paramNames as $paramName) {
            if (isset($matches[$paramName])) {
                $params[$paramName] = (string) $matches[$paramName];
            }
        }

        return $params;
    }

    /**
     * Menjalankan middleware chain lalu memanggil route handler.
     *
     * @param array<string, string> $params
     * @param array<int, callable(Request, callable(Request): Response, array<string, string>): Response> $routeMiddleware
     */
    private function runRoute(Request $request, array $params, callable $handler, array $routeMiddleware): Response
    {
        $finalHandler = static function (Request $currentRequest) use ($handler, $params): Response {
            $result = $handler($currentRequest, $params);

            if ($result instanceof Response) {
                return $result;
            }

            return Response::html((string) $result);
        };

        $pipeline = array_merge($this->globalMiddleware, $routeMiddleware);
        $next = $finalHandler;

        foreach (array_reverse($pipeline) as $middleware) {
            $previous = $next;

            $next = static function (Request $currentRequest) use ($middleware, $previous, $params): Response {
                return $middleware($currentRequest, $previous, $params);
            };
        }

        return $next($request);
    }

    /**
     * Menormalkan path agar konsisten: diawali `/` dan tanpa trailing slash (kecuali root).
     */
    private function normalizePath(string $path): string
    {
        if ($path === '' || $path === '/') {
            return '/';
        }

        return '/' . trim($path, '/');
    }
}
