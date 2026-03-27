<?php
// backend/db.php - PostgreSQL Connection Helper
require_once __DIR__ . '/env.php';

class Database
{
    private static $defaultConfig = [
        'host' => 'db.fnfqrectniyjpkyfkmal.supabase.co',
        'db' => 'postgres',
        'user' => 'postgres',
        'pass' => 'nrpsupabase7',
        'port' => '5432',
        'sslmode' => 'require',
    ];

    private static $pdo = null;
    private static $config = null;

    private static function readUrlConfig()
    {
        $urlEnvNames = ['SUPABASE_DB_URL', 'DATABASE_URL', 'POSTGRES_URL'];

        foreach ($urlEnvNames as $envName) {
            $url = getenv($envName);
            if ($url === false || $url === '') {
                continue;
            }

            $parts = parse_url($url);
            if ($parts === false) {
                continue;
            }

            parse_str($parts['query'] ?? '', $query);

            return array_filter([
                'host' => $parts['host'] ?? null,
                'db' => isset($parts['path']) ? ltrim($parts['path'], '/') : null,
                'user' => $parts['user'] ?? null,
                'pass' => $parts['pass'] ?? null,
                'port' => isset($parts['port']) ? (string) $parts['port'] : null,
                'sslmode' => $query['sslmode'] ?? null,
            ], static fn($value) => $value !== null && $value !== '');
        }

        return [];
    }

    private static function getConfig()
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $config = self::$defaultConfig;

        $localConfigFile = __DIR__ . '/db.local.php';
        if (file_exists($localConfigFile)) {
            $localConfig = require $localConfigFile;
            if (is_array($localConfig)) {
                $config = array_merge($config, array_filter($localConfig, static fn($value) => $value !== null && $value !== ''));
            }
        }

        $config = array_merge($config, self::readUrlConfig());

        $envMap = [
            'host' => 'AGRICARE_DB_HOST',
            'db' => 'AGRICARE_DB_NAME',
            'user' => 'AGRICARE_DB_USER',
            'pass' => 'AGRICARE_DB_PASS',
            'port' => 'AGRICARE_DB_PORT',
            'sslmode' => 'AGRICARE_DB_SSLMODE',
        ];

        foreach ($envMap as $key => $envName) {
            $value = getenv($envName);
            if ($value !== false && $value !== '') {
                $config[$key] = $value;
            }
        }

        $supabaseEnvMap = [
            'host' => 'SUPABASE_DB_HOST',
            'db' => 'SUPABASE_DB_NAME',
            'user' => 'SUPABASE_DB_USER',
            'pass' => 'SUPABASE_DB_PASS',
            'port' => 'SUPABASE_DB_PORT',
            'sslmode' => 'SUPABASE_DB_SSLMODE',
        ];

        foreach ($supabaseEnvMap as $key => $envName) {
            $value = getenv($envName);
            if ($value !== false && $value !== '') {
                $config[$key] = $value;
            }
        }

        if (
            (!empty($config['host']) && stripos($config['host'], 'supabase.co') !== false)
            || getenv('SUPABASE_DB_URL') !== false
        ) {
            $config['sslmode'] = $config['sslmode'] ?: 'require';
        }

        self::$config = $config;
        return self::$config;
    }

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                $config = self::getConfig();
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['db']}";
                if (!empty($config['sslmode'])) {
                    $dsn .= ";sslmode={$config['sslmode']}";
                }
                self::$pdo = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => true,
                ]);
                // Removed ensureUsersTableSchema(self::$pdo) to speed up performance.
                // Run update_db.php manually if schema changes are needed.
            } catch (PDOException $e) {
                throw $e;
            }
        }
        return self::$pdo;
    }
}

