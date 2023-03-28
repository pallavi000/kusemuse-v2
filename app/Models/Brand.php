<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Brand extends Model
{
    use HasFactory;
  
    protected $guarded = ['created_at', 'updated_at'];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

}
