<?php
// backend/db.php - PostgreSQL Connection Helper

class Database
{
    private static $host = 'db.fnfqrectniyjpkyfkmal.supabase.co';
    private static $db = 'postgres';
    private static $user = 'postgres';
    private static $pass = 'nrpsupabase7';
    private static $port = '5432';
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db . ";sslmode=require";
                self::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
                exit;
            }
        }
        return self::$pdo;
    }
}
