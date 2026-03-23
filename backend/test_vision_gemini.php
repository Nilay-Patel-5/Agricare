<?php
require_once __DIR__ . '/gemini_client.php';
$base64Data = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=';
$tempImage = tempnam(sys_get_temp_dir(), 'test');
file_put_contents($tempImage, base64_decode($base64Data));
$result = gemini_analyze_image($tempImage, 'image/png');
unlink($tempImage);
echo "Result: " . $result . "\n";
