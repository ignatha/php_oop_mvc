<?php

namespace App\Services;

class Connection {
    
    protected $host = '127.0.0.1';
    protected $db = 'belajar';
    protected $username = 'ignatha';
    protected $pass = 'aku';
    protected $conn = null;

    public function connect()
    {

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $rule = "mysql:host=".$this->host.";dbname=".$this->db;
            $this->conn = new \PDO($rule,$this->username, $this->pass, $options);
        } catch (\PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

}