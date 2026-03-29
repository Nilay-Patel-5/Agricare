<?php

// backend/env.php - Load .env file into environment variables
(function () {
    // Try both possible locations
    $candidates = [
        __DIR__ . '/../.env',
        __DIR__ . '/../../.env',
        dirname(__DIR__) . '/.env',
    ];

    $envFile = null;
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            $envFile = $candidate;
            break;
        }
    }

    if (!$envFile) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if ($key !== '' && getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
})();
