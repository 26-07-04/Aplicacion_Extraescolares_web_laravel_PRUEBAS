<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {

    require __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    $app->handleRequest(
        Illuminate\Http\Request::capture()
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo '<pre style="white-space: pre-wrap; font-family: Arial;">';
    echo 'ERROR DE LARAVEL EN VERCEL' . PHP_EOL . PHP_EOL;
    echo htmlspecialchars($e->getMessage()) . PHP_EOL . PHP_EOL;
    echo htmlspecialchars($e->getFile()) . PHP_EOL;
    echo 'Linea: ' . $e->getLine() . PHP_EOL . PHP_EOL;
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';

}