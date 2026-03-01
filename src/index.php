<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Http\Request;
use Http\Response;
use Todo\TodoNotFoundException;
use Todo\TodoRepositoryFactory;
use Todo\TodoService;
use Todo\ValidationException;
use View\View;

$config = Config::load(__DIR__ . '/config/app.php');
$repository = TodoRepositoryFactory::make($config);
$todoService = new TodoService($repository);
$view = new View(__DIR__ . '/Views');

$container = new Container();
$container->set(TodoService::class, $todoService);
$container->set(View::class, $view);

$router = new Router();
$router->setContainer($container);
$router->middleware(static function (Request $request, callable $next, array $params): Response {
    return $next($request);
});

require __DIR__ . '/routes.php';

$request = Request::fromGlobals();

try {
    $response = $router->dispatch($request);
} catch (ValidationException $exception) {
    $details = $exception->getDetails();

    if ($request->wantsJson()) {
        $response = Response::json([
            'error' => [
                'message' => $exception->getMessage(),
                'details' => $details,
            ],
        ], 422);
    } else {
        $html = $view->render('errors/error.php', [
            'title' => 'Validasi Gagal',
            'status' => 422,
            'message' => $exception->getMessage(),
            'details' => $details,
        ]);

        $response = Response::html($html, 422);
    }
} catch (TodoNotFoundException $exception) {
    $details = ['id' => $exception->getTodoId()];

    if ($request->wantsJson()) {
        $response = Response::json([
            'error' => [
                'message' => $exception->getMessage(),
                'details' => $details,
            ],
        ], 404);
    } else {
        $html = $view->render('errors/error.php', [
            'title' => 'Data Tidak Ditemukan',
            'status' => 404,
            'message' => $exception->getMessage(),
            'details' => $details,
        ]);

        $response = Response::html($html, 404);
    }
} catch (Throwable $exception) {
    if ($request->wantsJson()) {
        $response = Response::json([
            'error' => [
                'message' => 'Terjadi kesalahan internal server.',
                'details' => [
                    'exception' => $config->get('app.debug', false) ? $exception->getMessage() : 'internal_error',
                ],
            ],
        ], 500);
    } else {
        $html = $view->render('errors/error.php', [
            'title' => 'Server Error',
            'status' => 500,
            'message' => 'Terjadi kesalahan internal server.',
            'details' => $config->get('app.debug', false)
                ? ['exception' => $exception->getMessage()]
                : [],
        ]);

        $response = Response::html($html, 500);
    }
}

$response->send();
