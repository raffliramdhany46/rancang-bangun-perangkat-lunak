<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/View/View.php';
require __DIR__ . '/../src/Controller/TodoHtmlController.php';

use App\Controller\TodoHtmlController;
use App\View\View;

/**
 * @var array<int, array{name:string, passed:bool, message:string}>
 */
$results = [];

function addResult(string $name, bool $passed, string $message = ''): void
{
    global $results;
    $results[] = [
        'name' => $name,
        'passed' => $passed,
        'message' => $message,
    ];
}

function runTest(string $name, callable $test): void
{
    try {
        $test();
        addResult($name, true);
    } catch (Throwable $e) {
        addResult($name, false, $e->getMessage());
    } finally {
        if (function_exists('header_remove')) {
            header_remove();
        }
        http_response_code(200);
    }
}

function assertTrueValue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function assertSameValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        $expectedValue = var_export($expected, true);
        $actualValue = var_export($actual, true);
        throw new RuntimeException($message . " | expected={$expectedValue}, actual={$actualValue}");
    }
}

function assertContainsValue(string $needle, string $haystack, string $message): void
{
    if (strpos($haystack, $needle) === false) {
        throw new RuntimeException($message . " | needle={$needle}");
    }
}

runTest('e() escapes HTML entities', function (): void {
    $escaped = e('<script>alert("x")</script>');
    assertSameValue('&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', $escaped, 'Output e() tidak sesuai');
});

runTest('asset() builds asset URL', function (): void {
    assertSameValue('/assets/app.css', asset('app.css'), 'Path asset app.css salah');
    assertSameValue('/assets/js/app.js', asset('/js/app.js'), 'Path asset dengan slash awal salah');
});

runTest('config() reads existing key and fallback default', function (): void {
    $dbHost = config('db.host');
    assertTrueValue(is_string($dbHost) && $dbHost !== '', 'db.host harus string non-empty');

    $default = config('db.key_tidak_ada', 'default-value');
    assertSameValue('default-value', $default, 'Fallback default config tidak bekerja');
});

runTest('View::render() renders about view with layout', function (): void {
    ob_start();
    View::render('about', [
        'title' => 'Test About',
        'pageHeading' => 'Tentang Unit Test',
    ]);
    $html = (string) ob_get_clean();

    assertContainsValue('<title>Test About</title>', $html, 'Title layout tidak ditemukan');
    assertContainsValue('<h2>Tentang Unit Test</h2>', $html, 'Heading view about tidak ditemukan');
    assertContainsValue('Create Todo', $html, 'Menu layout tidak ter-render');
});

runTest('View::render() throws when view does not exist', function (): void {
    try {
        View::render('tidak-ada-view');
    } catch (RuntimeException $e) {
        assertContainsValue('View tidak ditemukan', $e->getMessage(), 'Pesan exception view tidak sesuai');
        return;
    }

    throw new RuntimeException('RuntimeException tidak dilempar untuk view yang tidak ada');
});

runTest('TodoHtmlController::markDone() rejects non-POST method', function (): void {
    $originalServer = $_SERVER;
    $originalPost = $_POST;

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_POST = [];

    $controller = new TodoHtmlController();

    ob_start();
    $controller->markDone();
    $output = (string) ob_get_clean();

    $_SERVER = $originalServer;
    $_POST = $originalPost;

    assertSameValue(405, http_response_code(), 'Status code untuk non-POST harus 405');
    $payload = json_decode($output, true);
    assertTrueValue(is_array($payload), 'Payload markDone non-POST harus JSON object');
    assertSameValue(false, $payload['success'] ?? null, 'success harus false');
    assertSameValue('Method not allowed.', $payload['message'] ?? null, 'Pesan error non-POST tidak sesuai');
});

runTest('TodoHtmlController::markDone() validates id', function (): void {
    $originalServer = $_SERVER;
    $originalPost = $_POST;

    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = ['id' => '0'];

    $controller = new TodoHtmlController();

    ob_start();
    $controller->markDone();
    $output = (string) ob_get_clean();

    $_SERVER = $originalServer;
    $_POST = $originalPost;

    assertSameValue(422, http_response_code(), 'Status code id invalid harus 422');
    $payload = json_decode($output, true);
    assertTrueValue(is_array($payload), 'Payload markDone id invalid harus JSON object');
    assertSameValue(false, $payload['success'] ?? null, 'success harus false untuk id invalid');
    assertSameValue('Parameter id tidak valid.', $payload['message'] ?? null, 'Pesan id invalid tidak sesuai');
});

$passed = 0;
$failed = 0;

foreach ($results as $result) {
    if ($result['passed']) {
        $passed++;
        echo "[PASS] {$result['name']}" . PHP_EOL;
        continue;
    }

    $failed++;
    echo "[FAIL] {$result['name']} - {$result['message']}" . PHP_EOL;
}

echo PHP_EOL;
echo "Total: " . count($results) . ", Passed: {$passed}, Failed: {$failed}" . PHP_EOL;

exit($failed > 0 ? 1 : 0);
