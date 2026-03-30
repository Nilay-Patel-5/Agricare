<?php

return [
    'base_url' => getenv('OLLAMA_BASE_URL') ?: 'http://127.0.0.1:11434',
    'model'    => getenv('OLLAMA_MODEL') ?: 'llama3.2:1b',
    'timeout'  => (int) (getenv('OLLAMA_TIMEOUT') ?: 120),
];
