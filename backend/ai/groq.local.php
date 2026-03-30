<?php

return [
    'api_key'  => getenv('GROQ_API_KEY') ?: '',
    'base_url' => getenv('GROQ_BASE_URL') ?: 'https://api.groq.com/openai/v1',
    'model'    => getenv('GROQ_MODEL') ?: 'llama-3.1-8b-instant',
    'timeout'  => (int) (getenv('GROQ_TIMEOUT') ?: 60),
];
