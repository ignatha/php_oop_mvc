<?php

namespace App\Model;

use App\Services\Model;

class User extends Model {
    protected $table = "user";
    protected $primaryKey = "id";
    protected $fillable = ['username','name','password','img'];
}