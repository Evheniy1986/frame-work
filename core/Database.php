<?php

namespace Framework;

class Database
{
    private static ?Database $instance = null;
    protected static ?\PDO $connection = null;
    private \PDOStatement $stmt;

    private function __construct()
    {
            $this->connect();

    }

    private function connect()
    {

        if (self::$connection !== null) {
            return;
        }

        $dsn = sprintf("%s:host=%s;dbname=%s;charset=%s",
            DATABASE_CONFIG['driver'],
            DATABASE_CONFIG['host'],
            DATABASE_CONFIG['db_name'],
            DATABASE_CONFIG['charset']
        );
        try {
            self::$connection = new \PDO(
                $dsn,
                DATABASE_CONFIG['username'],
                DATABASE_CONFIG['password'],
                DATABASE_CONFIG['options']
            );
        } catch (\PDOException $exception) {
            error_log(
                "[". date('Y-m-d H:i:s'). "Db error: {$exception->getMessage()}" . PHP_EOL,
                3,
                APP_PATH . '/tmp/errors.log'
            );
            exit("Database connection error: {$exception->getMessage()}");
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function sqlQuery($query, $params = []): static
    {

        $this->stmt = self::$connection->prepare($query);

        // Отладка SQL запроса
        $debugSql = $query;
        foreach ($params as $key => $value) {
            $value = self::$connection->quote($value);
            if (is_string($key)) {
                $debugSql = str_replace(":$key", $value, $debugSql);
            } else {
                $debugSql = preg_replace('/\?/', $value, $debugSql, 1);
            }
        }
        error_log("Executing SQL: $debugSql", 3, APP_PATH . '/tmp/errors.log');
//        dump($debugSql);

        $this->stmt->execute($params);

        return $this;
    }

    public function get()
    {
        return $this->stmt->fetchAll();
    }

    public function getOne()
    {
        return $this->stmt->fetch();
    }

    public function getInsertId()
    {
        return self::$connection->lastInsertId();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public static function getConection(): \PDO
    {
        return self::$connection ?? self::getInstance()->connect();
    }
}

