<?php

// Copy this file to grok.local.php and set GROK_API_KEY as an environment variable.
// Get your Grok API key from https://console.x.ai/
return [
    'api_key'  => getenv('GROK_API_KEY') ?: '',
    'base_url' => getenv('GROK_BASE_URL') ?: 'https://api.x.ai/v1',
    'model'    => getenv('GROK_MODEL') ?: 'grok-2-latest',
    'timeout'  => (int) (getenv('GROK_TIMEOUT') ?: 60),
];
