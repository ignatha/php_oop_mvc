<?php

namespace App\Services;

class Connection {
    
    protected $conn = null;

    public function connect()
    {

        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $username = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $rule = "mysql:host=".$host.";dbname=".$db;
            $this->conn = new \PDO($rule,$username, $pass, $options);
        } catch (\PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

}