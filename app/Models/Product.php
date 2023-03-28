<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Subcategory;
use App\Models\Color;
use App\Models\Size;
use App\Models\Banner;
use App\Models\Image_product;
use App\Models\Specification;



class Product extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    
    public function newQuery() {
        return parent::newQuery()
            ->where('visible', true);
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function brand(){
        return $this->belongsTo('App\Models\Brand');
    }
    
    public function color(){
        return $this->belongsTo('App\Models\Color');
    }
   
    public function colors()
    {
        return $this->belongsToMany('App\Models\Color');
    }
    public function sizes()
    {
        return $this->belongsToMany('App\Models\Size')->withPivot('stock','price','sku');
    }

    public function images(){
        return $this->belongsToMany('App\Models\Image_product');
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class,'product_id', 'id');
    } 

    public function review(){
        return $this->blongsTo('App\Models\Review');
    }

    public function designer(){
        return $this->belongsTo('App\Models\Designer');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    

    
}

