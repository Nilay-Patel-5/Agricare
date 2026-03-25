<?php

return [
    // Set GEMINI_API_KEY as an environment variable, or copy this file to
    // gemini.local.php and replace the getenv() fallback with your key (local dev only).
    // Get your free Gemini API key from https://aistudio.google.com/app/apikey
    'api_key' => getenv('GEMINI_API_KEY') ?: '',
];
