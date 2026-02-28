<?php

declare(strict_types=1);

use Controller\TodoApiController;
use Controller\TodoHtmlController;
use Http\Response;

/** @var Router $router */
/** @var TodoHtmlController $todoHtmlController */
/** @var TodoApiController $todoApiController */

$router->get('/', static fn ($request, $params): Response => Response::redirect('/todos'));

// HTML routes
$router->get('/todos', [$todoHtmlController, 'index']);
$router->get('/todos/create', [$todoHtmlController, 'createForm']);
$router->post('/todos', [$todoHtmlController, 'store']);
$router->get('/todos/{id}/edit', [$todoHtmlController, 'editForm']);
$router->put('/todos/{id}', [$todoHtmlController, 'update']);
$router->patch('/todos/{id}', [$todoHtmlController, 'update']);
$router->delete('/todos/{id}', [$todoHtmlController, 'destroy']);
$router->post('/todos/{id}/done', [$todoHtmlController, 'done']);

// API routes
$router->get('/api/todos', [$todoApiController, 'index']);
$router->get('/api/todos/{id}', [$todoApiController, 'show']);
$router->post('/api/todos', [$todoApiController, 'store']);
$router->patch('/api/todos/{id}', [$todoApiController, 'update']);
$router->delete('/api/todos/{id}', [$todoApiController, 'destroy']);
$router->post('/api/todos/{id}/done', [$todoApiController, 'done']);
