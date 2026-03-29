<?php
$key = 'AIzaSyA1ZBwZCmANpoEJdrnrbisTiKtQOxhWk5M';
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $key;
$ch = curl_init($url);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_TIMEOUT => 15]);
$resp = curl_exec($ch);
$data = json_decode($resp, true);
if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        // Only show models that support generateContent
        $methods = array_column($m['supportedGenerationMethods'] ?? [], null);
        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
            echo $m['name'] . "\n";
        }
    }
} else {
    echo $resp;
}
