<?php
/**
 * Database Connection Singleton
 * Restaurant Management System
 */

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    private $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/database.php';
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $dbPath = $this->resolveWritableDbPath($this->config['database']);

            $this->connection = new PDO(
                "sqlite:{$dbPath}",
                null,
                null,
                $this->config['options']
            );

            try {
                $this->connection->exec('PRAGMA journal_mode = WAL');
            } catch (\Throwable $e) {
                // ignore
            }
            try {
                $this->connection->exec('PRAGMA foreign_keys = ON');
                $this->connection->exec('PRAGMA cache_size = -16000');
            } catch (\Throwable $e) {
                // non-fatal
            }

        } catch (PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    private function resolveWritableDbPath(string $dbPath): string
    {
        $dbDir = dirname($dbPath);

        if (!is_dir($dbDir)) {
            @mkdir($dbDir, 0755, true);
        }

        if (!is_dir($dbDir) || !is_writable($dbDir)) {
            $fallbackDir = rtrim(sys_get_temp_dir(), '/') . '/dzieres';
            if (!is_dir($fallbackDir)) {
                @mkdir($fallbackDir, 0755, true);
            }
            if (is_dir($fallbackDir) && is_writable($fallbackDir)) {
                $dbPath = $fallbackDir . '/restaurant.db';
            }
        }

        if (file_exists($dbPath) && filesize($dbPath) === 0) {
            @unlink($dbPath);
        }

        return $dbPath;
    }

    public function getWritableDbPath(): string
    {
        return $this->resolveWritableDbPath($this->config['database']);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        $row = $result->fetch();
        return $row ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return (int) $this->connection->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE {$where}";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_merge($data, $whereParams));
        
        return $stmt->rowCount();
    }

    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    public function tableExists(string $table): bool
    {
        $result = $this->fetch(
            "SELECT name FROM sqlite_master WHERE type='table' AND name=?",
            [$table]
        );
        return $result !== null;
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() 
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
