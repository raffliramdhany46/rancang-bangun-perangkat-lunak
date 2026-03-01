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

    public function store() : void{
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST') { 
            // Simulasi penyimpanan data
            $title = $_POST['title'] ?? 'Untitled';
            // Di sini Anda bisa menambahkan logika untuk menyimpan data ke database atau file
            echo "Todo dengan judul '{$title}' telah disimpan.";
            // Setelah menyimpan, redirect ke halaman daftar todo
            // header('Location: /todos');
            exit();
        }
    }
}
