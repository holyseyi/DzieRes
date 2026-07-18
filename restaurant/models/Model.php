<?php
/**
 * Base Model
 * Provides a thin, reusable ActiveRecord-style wrapper around the PDO database.
 * Restaurant Management System
 */

namespace Models;

use Config\Database;

abstract class Model
{
    /** @var string The database table name (override in child classes) */
    protected string $table = '';

    /** @var string Primary key column */
    protected string $primaryKey = 'id';

    /** @var array Fields that are JSON encoded when read/written */
    protected array $jsonFields = [];

    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a single record by primary key.
     */
    public function find(int $id): ?object
    {
        $result = $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
        return $result ? $this->decodeJson($result) : null;
    }

    /**
     * Find a single record by a column value.
     */
    public function findBy(string $column, $value): ?object
    {
        $result = $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
        return $result ? $this->decodeJson($result) : null;
    }

    /**
     * Get all records optionally filtered by a where clause.
     */
    public function all(string $where = '', array $params = [], string $order = ''): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        $rows = $this->db->fetchAll($sql, $params);
        return array_map([$this, 'decodeJson'], $rows);
    }

    /**
     * Insert a record and return the new id.
     */
    public function create(array $data): int
    {
        $data = $this->encodeJson($data);
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update a record by primary key.
     */
    public function update(int $id, array $data): int
    {
        $data = $this->encodeJson($data);
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    /**
     * Delete a record by primary key.
     */
    public function delete(int $id): int
    {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Raw query helper.
     */
    public function query(string $sql, array $params = []): array
    {
        return $this->db->fetchAll($sql, $params);
    }

    public function queryOne(string $sql, array $params = []): ?object
    {
        return $this->db->fetch($sql, $params);
    }

    /**
     * Count rows matching a where clause.
     */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->fetch($sql, $params);
        return $result ? (int)$result->count : 0;
    }

    /**
     * Encode JSON fields before persisting.
     */
    protected function encodeJson(array $data): array
    {
        foreach ($this->jsonFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field], JSON_UNESCAPED_UNICODE);
            }
        }
        return $data;
    }

    /**
     * Decode JSON fields after fetching.
     */
    protected function decodeJson(object $row): object
    {
        foreach ($this->jsonFields as $field) {
            if (isset($row->$field) && is_string($row->$field)) {
                $decoded = json_decode($row->$field, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $row->$field = $decoded;
                }
            }
        }
        return $row;
    }
}
