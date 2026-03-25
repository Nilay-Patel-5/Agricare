<?php

// Copy this file to db.local.php for local development.
// Set these environment variables in your server/hosting config or .env file.
return [
    'host'    => getenv('SUPABASE_DB_HOST') ?: 'db.your-project-ref.supabase.co',
    'db'      => getenv('SUPABASE_DB_NAME') ?: 'postgres',
    'user'    => getenv('SUPABASE_DB_USER') ?: '',
    'pass'    => getenv('SUPABASE_DB_PASS') ?: '',
    'port'    => getenv('SUPABASE_DB_PORT') ?: '5432',
    'sslmode' => getenv('SUPABASE_DB_SSLMODE') ?: 'require',
];
