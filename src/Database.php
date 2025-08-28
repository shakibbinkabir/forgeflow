<?php
namespace ForgeFlow;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/app.php';
        $db = $config['database'];
        
        try {
            // First, connect to MySQL server without specifying DB to ensure database exists
            $serverDsn = "mysql:host={$db['host']};charset={$db['charset']}";
            $serverPdo = new \PDO($serverDsn, $db['username'], $db['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $dbName = preg_replace('/[^a-zA-Z0-9_\-]/', '', $db['dbname']);
            $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$db['charset']} COLLATE {$db['charset']}_general_ci");

            // Now connect to the specific database
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $this->connection = new \PDO($dsn, $db['username'], $db['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            // For development, create SQLite database if MySQL is not available
            try {
                $this->connection = new \PDO("sqlite:" . __DIR__ . "/../database/forgeflow.db", null, null, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]);
            } catch (\PDOException $e2) {
                die("Database connection failed: " . $e2->getMessage());
            }
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}