<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

class Wishlist extends Model
{
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

    public function sizes(){
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }


public function product_size(){
    return $this->belongsTo(Product_Size::class, 'size_id', 'size_id');
}

}
