<?php

declare(strict_types=1);

/** @var Router $router */

$router->get('/', static function (): string {
    return 'Hello World';
});

$router->get('/about', static function (): string {
    return 'About Page';
});

