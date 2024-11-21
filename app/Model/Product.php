<?php

namespace App\Model;

use App\Services\Model;

class Product extends Model {
    protected $table = "product";
    protected $primaryKey = "id";
    protected $fillable = ['username','name','password'];

    
}