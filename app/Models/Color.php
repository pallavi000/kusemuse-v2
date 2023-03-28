<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Color extends Model
{
    use HasFactory;
    protected $table= 'colors';
    protected $guarded=['created_at','updated_at'];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
}
