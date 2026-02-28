<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/TestCase.php';
require __DIR__ . '/RouterTest.php';
require __DIR__ . '/TodoServiceTest.php';

$tests = [
    new RouterTest(),
    new TodoServiceTest(),
];

foreach ($tests as $test) {
    $test->run();
}

foreach ($tests as $test) {
    $test->finalize();
}

echo 'Semua test selesai.' . PHP_EOL;
