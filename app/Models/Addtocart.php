<?php

namespace App\Models;
use App\Models\Product;
use App\Models\User;
use App\Models\Size;
use App\Models\Product_Size;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Addtocart extends Model
{
    use \Awobaz\Compoships\Compoships;


    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function product_size(){
        return $this->belongsTo(Product_Size::class,['size_id','product_id'],['size_id','product_id']);
    }

    public function sizes(){
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
    
}

