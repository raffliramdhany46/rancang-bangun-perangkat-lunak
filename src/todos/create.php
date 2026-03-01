<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/Controller/TodoHtmlController.php';

use App\Controller\TodoHtmlController;

$controller = new TodoHtmlController();
$controller->create();
