<?php

declare(strict_types=1);

namespace View;

/**
 * Renderer sederhana untuk template PHP dengan layout reusable.
 */
final class View
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Me-render template dan membungkusnya dengan layout.
     *
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = [], ?string $layout = 'layout.php'): string
    {
        $templatePath = $this->basePath . '/' . ltrim($template, '/');

        if (!is_file($templatePath)) {
            throw new \RuntimeException('Template tidak ditemukan: ' . $templatePath);
        }

        $content = $this->renderFile($templatePath, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutPath = $this->basePath . '/' . ltrim($layout, '/');

        if (!is_file($layoutPath)) {
            throw new \RuntimeException('Layout tidak ditemukan: ' . $layoutPath);
        }

        $layoutData = array_merge($data, ['content' => $content]);

        return $this->renderFile($layoutPath, $layoutData);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderFile(string $filePath, array $data): string
    {
        extract($data, EXTR_SKIP);

        ob_start();
        include $filePath;

        return (string) ob_get_clean();
    }
}
