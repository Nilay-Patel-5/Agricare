<?php
session_start();

// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz';
$randomString = '';
for ($i = 0; $i < 5; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
}
$_SESSION['captcha_code'] = $randomString;

header('Content-Type: application/json');
echo json_encode(['success' => true, 'captcha' => $randomString]);
