<?php

namespace Core;

use Core\Request;

class DB
{
    protected static $instance = null;
    protected $pdo;
    protected $table;
    protected $columns = '*';
    protected $joins = [];
    protected $where = [];
    protected $orWhere = [];
    protected $bindings = [];
    protected $groupBy = null;
    protected $having = null;
    protected $orderBy = null;
    protected $limit = null;
    protected $offset = null;

    protected function __construct()
    {
        $config = require CONFIG . '/database' . EXT;

        $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['pass'], $options);
        } catch (\PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function table($table)
    {
        $instance = static::getInstance();
        $instance->resetQuery();
        $instance->table = $table;
        return $instance;
    }

    protected function resetQuery()
    {
        $this->columns = '*';
        $this->joins = [];
        $this->where = [];
        $this->orWhere = [];
        $this->bindings = [];
        $this->groupBy = null;
        $this->having = null;
        $this->orderBy = null;
        $this->limit = null;
        $this->offset = null;
    }

    public function select($columns = '*')
    {
        $this->columns = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $table = $this->escape($table);
        $first = $this->escape($first);
        $second = $this->escape($second);
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $column = $this->escape($column);
        $this->where[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $column = $this->escape($column);
        $this->orWhere[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function groupBy($column)
    {
        $column = $this->escape($column);
        $this->groupBy = " GROUP BY $column";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $column = $this->escape($column);
        $this->orderBy = " ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit, $offset = null)
    {
        $this->limit = " LIMIT $limit";
        if ($offset !== null) {
            $this->offset = " OFFSET $offset";
        }
        return $this;
    }

    public function get()
    {
        $table = $this->escape($this->table);
        $columns = $this->columns === '*' ? '*' : implode(', ', array_map([$this, 'escape'], explode(', ', $this->columns)));

        $sql = "SELECT $columns FROM $table";

        if (!empty($this->joins)) {
            $sql .= " " . implode(' ', $this->joins);
        }

        $whereSql = "";
        if (!empty($this->where)) {
            $whereSql .= implode(' AND ', $this->where);
        }

        if (!empty($this->orWhere)) {
            $whereSql = ($whereSql ? "($whereSql) OR " : "") . implode(' OR ', $this->orWhere);
        }

        if ($whereSql) {
            $sql .= " WHERE $whereSql";
        }

        $sql .= $this->groupBy ?? '';
        $sql .= $this->orderBy ?? '';
        $sql .= $this->limit ?? '';
        $sql .= $this->offset ?? '';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    public function first()
    {
        $result = $this->limit(1)->get();
        return $result[0] ?? null;
    }

    public function count()
    {
        $this->columns = "COUNT(*) as count";
        $result = $this->first();
        return (int) ($result['count'] ?? 0);
    }

    public function paginate($perPage = 15)
    {
        $page = Request::get('page', 1);
        $total = $this->count();
        $this->columns = '*'; // Reset columns after count

        $offset = ($page - 1) * $perPage;
        $data = $this->limit($perPage, $offset)->get();

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function insert($data)
    {
        $columns = implode(', ', array_map([$this, 'escape'], array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $table = $this->escape($this->table);
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update($data)
    {
        $sets = [];
        $values = [];
        foreach ($data as $column => $value) {
            $sets[] = $this->escape($column) . " = ?";
            $values[] = $value;
        }

        $table = $this->escape($this->table);
        $sql = "UPDATE $table SET " . implode(', ', $sets);

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($values, $this->bindings));
    }

    public function delete()
    {
        $table = $this->escape($this->table);
        $sql = "DELETE FROM $table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * Identifier'ları (tablo/sütun isimleri) güvenli hale getirir (backtick ekler).
     */
    protected function escape($identifier)
    {
        if ($identifier === '*') {
            return '*';
        }

        // Eğer zaten fonksiyon veya özel karakterler içeriyorsa elleme (basit kontrol)
        if (strpos($identifier, '(') !== false || strpos($identifier, ' AS ') !== false) {
            return $identifier;
        }

        $parts = explode('.', str_replace('`', '', $identifier));
        $escapedParts = array_map(function ($part) {
            return ($part === '*') ? '*' : "`$part`";
        }, $parts);

        return implode('.', $escapedParts);
    }
}
