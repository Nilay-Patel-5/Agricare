<?php

return [
    'api_key'  => getenv('GROK_API_KEY') ?: '',
    'base_url' => getenv('GROK_BASE_URL') ?: 'https://api.x.ai/v1',
    'model'    => getenv('GROK_MODEL') ?: 'grok-beta',
    'timeout'  => (int) (getenv('GROK_TIMEOUT') ?: 60),
];
