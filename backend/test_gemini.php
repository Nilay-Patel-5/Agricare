<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/ai/gemini.php';

$res = gemini_text_create([['role' => 'user', 'content' => 'Hello']], 'gemini-1.5-flash');
print_r($res);
