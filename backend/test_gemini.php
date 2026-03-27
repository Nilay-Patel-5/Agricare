<?php
require_once __DIR__ . '/gemini_client.php';
$config = gemini_config();
$ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models?key=' . $config['api_key']);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_SSL_VERIFYPEER=>0, CURLOPT_SSL_VERIFYHOST=>0]);
file_put_contents('gemini_models.json', curl_exec($ch));
echo "Models saved";
