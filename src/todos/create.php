<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/Controller/TodoHtmlController.php';

use App\Controller\TodoHtmlController;

$controller = new TodoHtmlController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        $controller->store();
        exit;
}

$controller->create();
