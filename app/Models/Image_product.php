<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Image_product extends Model
{
    use HasFactory;
    protected $table = 'image_product';

    protected $guarded= ['created_at','updated_at'];

    public function products(){

        return $this->belongsTo(Product::class);
    }
    
}
