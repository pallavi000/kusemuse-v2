<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Size extends Model
{
    use \Awobaz\Compoships\Compoships;


    use HasFactory;
    Protected $table = 'product_size';
    protected $guarded=[];

    public function product(){
       return $this->belongsTo('App\Models\Product');
    }
}
