<?php

namespace App\Services;

use App\Services\Connection;

class Model extends Connection {

    protected $table;
    protected $primaryKey;
    protected $fillable = [];
    protected $attributes = [];

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
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':column',$value);

        $stmt->execute();

        return $stmt->fetchAll();

    }

    




}