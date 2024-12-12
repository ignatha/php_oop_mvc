<?php

namespace App\Services;

use App\Services\Connection;

class Model extends Connection {

    protected $table;
    protected $primaryKey;
    protected $fillable = [];
    protected $attributes = [];
    protected $stmt;

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function create(array $data)
    {
        // fungsi simpan kolom ke attributes
        foreach ($data as $key => $value) {
           if (in_array($key, $this->fillable)) { // apakah kolom ini fillble atau bukan
                $this->attributes[$key] = $value;
           }
        }

        // pisahkan kolom dan isi
        $columns = array_keys($this->attributes); // ['username','name','password'];
        $values = array_values($this->attributes); // ['subagio','nama subagio','ini password'];


        $placeholder = array_fill(0, count($columns), '?'); // ['?','?','?'];

        $sql = "INSERT INTO {$this->table} (". implode(', ',$columns) .") VALUES (". implode(', ',$placeholder) .")";

        // INSERT INTO user (username, name, password) VALUES (?, ?, ?);

        $stmt = $this->connect()->prepare($sql);

        return $stmt->execute($values);

    }

    public function update($id, array $data)
    {
 
        //  buat placeholder
        $columns = [];
        foreach ($data as $key => $value) {
           $columns[] = "{$key} = :{$key}";
        }

        // ["username = :username", "user = :user", "password = :password"]

        $placeholder = implode(', ',$columns); // "username = :username, user = :user, password = :password"

        $sql = "UPDATE {$this->table} SET {$placeholder} WHERE {$this->primaryKey} = :id";

        // UPDATE user SET "username = :username, user = :user, password = :password" WHERE id = :id;

        $stmt = $this->connect()->prepare($sql);

        $stmt->bindParam(':id',$id, \PDO::PARAM_INT); // id saja

        foreach ($data as $key => &$value) { // harus pakai referensi
            $stmt->bindParam(":{$key}",$value); // $stmt->bindParam(":user",$user);
        }

        return $stmt->execute();        
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id',$id,\PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function where($column,$compare,$value)
    {

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$compare} :column";
        $this->stmt = $this->connect()->prepare($sql);
        $this->stmt->bindParam(':column',$value);

        return $this;

    }

    public function get(){
        $this->stmt->execute();
        return $this->stmt->fetchAll();
    }

    public function first(){
        $this->stmt->execute();
        return $this->stmt->fetch();
    }

    public function paginate($perPage = 10, $currentPage = 1)
    {
        // data mulai yang akan di cari
        $offset = ($currentPage - 1)  * $perPage;

        // query untuk ambil data
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        $stmt = $this->connect()->prepare($sql);

        $stmt->bindValue(':limit',$perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset',$offset, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll();

        // query untuk menghitung total semua data
        $countStmt = $this->connect()->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $countStmt->execute();

        $total = $countStmt->fetch()->total;

        $totalPages = ceil($total / $perPage);

        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages
            ]
        ];


    }




}