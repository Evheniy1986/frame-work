<?php

namespace Framework;


class Model
{
    protected array $fillable = [];
    protected static string $table;
    public array $attributes = [];
    protected array $query = [
        'select' => '*',
        'where' => [],
        'params' => [],
        'limit' => null,
        'orderBy' => null,
    ];

    public function __construct($data = [])
    {
        $this->loadData($data ?: request()->all());
    }

    public function loadData(array $data)
    {
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            } else {
                $this->attributes[$field] = '';
            }
        }
    }


    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function __set($key, $value)
    {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function save(array $data = [])
    {
        if (empty($data)) {
            $data = $this->attributes;
        } else {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($field) => ":$field", array_keys($data)));

        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";

        $db = Database::getInstance();
        $db->sqlQuery($sql, $data);

        return $db->getInsertId();
    }

    public function update($data)
    {
        $id = $this->attributes['id'] ?? request()->input('id') ?? $data['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID записи не найден. Обновление невозможно.");
        }
        $setColumns = implode( ', ', array_map( fn($field) => "$field = :$field",  array_keys($data)));
        $sql = "UPDATE " . static::$table . " SET $setColumns WHERE id = :id";
        $data['id'] = $id;
        $db = Database::getInstance();
        $result = $db->sqlQuery($sql, $data);
        if ($result->rowCount() > 0) {
            foreach ($data as $key => $value) {
                $this->attributes[$key] = $value;
            }
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = ?";
        $result =  Database::getInstance()->sqlQuery($sql, [$id]);
        return $result->rowCount() > 0;
    }

    public function where($field, $operator, $value)
    {
        $this->query['where'][] = "$field $operator :$field";
        $this->query['params'][$field] = $value;
        return $this;
    }

    public function select($select)
    {
        $this->query['select'] = is_array($select) ? implode(', ', $select) : $select;
        return $this;
    }


    public function limit($limit)
    {
        $this->query['limit'] = $limit;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->query['orderBy'] = "$field $direction";
        return $this;
    }

    protected function buildQuery()
    {
        $sql = "SELECT {$this->query['select']} FROM " . static::$table;

        if (!empty($this->query['where'])) {
            $sql .= ' WHERE ' . implode(' AND ', $this->query['where']);
        }

        if (!empty($this->query['orderBy'])) {
            $sql .= " ORDER BY {$this->query['orderBy']}";
        }

        if (!empty($this->query['limit'])) {
            $sql .= " LIMIT {$this->query['limit']}";
        }

        return $sql;
    }

    public function get()
    {
        $sql = $this->buildQuery();
        return Database::getInstance()->sqlQuery($sql, $this->query['params'])->get();
    }

    public function first()
    {
        $sql = $this->buildQuery();
        return Database::getInstance()->sqlQuery($sql, $this->query['params'])->getOne();
    }

    public static function query(): static
    {
        return new static();
    }

    public static function all()
    {
        $table = static::$table;
        $sql = "SELECT * FROM $table";
        return Database::getInstance()->sqlQuery($sql)->get();
    }

    public function exists(array $conditions): bool
    {
        $whereConditions = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        $sql = "SELECT COUNT(*) FROM " . static::$table . " WHERE $whereConditions";

        $db = Database::getInstance();
        $result = $db->sqlQuery($sql, $conditions)->getOne();

        return (int) $result > 0;
    }

    public function count(array $conditions = []): int
    {
        $whereConditions = '';
        if (!empty($conditions)) {
            $whereConditions = 'WHERE ' . implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        }

        $sql = "SELECT COUNT(*) FROM " . static::$table . " $whereConditions";

        $db = Database::getInstance();
        $result = $db->sqlQuery($sql, $conditions)->getOne();

        return (int) $result;
    }

}