<?php
require_once __DIR__ . '/../backend/env.php';
require_once __DIR__ . '/../backend/gemini_client.php';
$c = gemini_config();
echo 'Key present: ' . (!empty($c['api_key']) ? 'YES' : 'NO') . PHP_EOL;
echo 'Model: ' . ($c['vision_model'] ?? 'N/A') . PHP_EOL;
