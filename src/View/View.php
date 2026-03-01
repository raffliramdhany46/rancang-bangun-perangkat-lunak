<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'layout'): void
    {
        $viewsDir = dirname(__DIR__) . '/Views';
        $viewPath = $viewsDir . '/' . trim($view, '/') . '.php';
        $layoutPath = $viewsDir . '/' . trim($layout, '/') . '.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException('View tidak ditemukan: ' . $viewPath);
        }

        if (!is_file($layoutPath)) {
            throw new RuntimeException('Layout tidak ditemukan: ' . $layoutPath);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = (string) ob_get_clean();

        require $layoutPath;
    }
}
