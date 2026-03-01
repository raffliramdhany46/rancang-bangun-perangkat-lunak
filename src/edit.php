<?php 

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/View/View.php';
use App\View\View;

View::render('edit', [
    'title' => 'Edit Todo',
    'pageHeading' => 'Edit Todo',
    'todos' => $todo,
]);