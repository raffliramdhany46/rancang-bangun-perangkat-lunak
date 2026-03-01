<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/View/View.php';

use App\View\View;

View::render('dashboard', [
    'title' => 'Home',
    'pageHeading' => 'Halaman dashboard',
    'angka' => [1,2,3,4,5],
    
]);