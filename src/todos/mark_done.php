<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../Controller/TodoHtmlController.php';

use App\Controller\TodoHtmlController;

$controller = new TodoHtmlController();
$controller->markDone();
