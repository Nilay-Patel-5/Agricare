<?php

return [
    'api_key'    => getenv('OPENAI_API_KEY') ?: '',
    'model'      => getenv('AGRICARE_OPENAI_MODEL') ?: 'gpt-4o-mini',
    'base_url'   => getenv('OPENAI_BASE_URL') ?: 'https://api.openai.com/v1',
    'timeout'    => (int) (getenv('AGRICARE_OPENAI_TIMEOUT') ?: 45),
    'verify_ssl' => true,
    'ca_info'    => getenv('OPENAI_CA_INFO') ?: '',
];
