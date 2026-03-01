<?php

declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../View/View.php';

use App\View\View;

final class TodoHtmlController
{
    public function create(): void
    {
        View::render('todos/create', [
            'title' => 'Buat Todo',
            'pageHeading' => 'Form Tambah Todo',
        ]);
    }
}
