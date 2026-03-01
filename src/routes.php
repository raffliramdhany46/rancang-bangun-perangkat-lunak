<?php

declare(strict_types=1);

use Controller\TodoApiController;
use Controller\TodoHtmlController;
use Http\Response;

/** @var Router $router */

$router->get('/', static fn ($request, $params): Response => Response::redirect('/todos'));

// HTML routes
$router->get('/todos', [TodoHtmlController::class, 'index']);
$router->get('/todos/create', [TodoHtmlController::class, 'createForm']);
$router->post('/todos', [TodoHtmlController::class, 'store']);
$router->get('/todos/{id}/edit', [TodoHtmlController::class, 'editForm']);
$router->put('/todos/{id}', [TodoHtmlController::class, 'update']);
$router->patch('/todos/{id}', [TodoHtmlController::class, 'update']);
$router->delete('/todos/{id}', [TodoHtmlController::class, 'destroy']);
$router->post('/todos/{id}/done', [TodoHtmlController::class, 'done']);

// API routes
$router->get('/api/todos', [TodoApiController::class, 'index']);
$router->get('/api/todos/{id}', [TodoApiController::class, 'show']);
$router->post('/api/todos', [TodoApiController::class, 'store']);
$router->patch('/api/todos/{id}', [TodoApiController::class, 'update']);
$router->delete('/api/todos/{id}', [TodoApiController::class, 'destroy']);
$router->post('/api/todos/{id}/done', [TodoApiController::class, 'done']);
