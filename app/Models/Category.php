<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;


class Category extends Model
{

    use HasFactory;

    protected $guarded = ['created_at','updated_at'];

    public function categories()
    {
        return $this->hasMany(Category::class,'parent_id');
    }
    
    public function childrenCategories()
    {
    return $this->hasMany(Category::class,'parent_id')->with('categories');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class,'id', 'category_id');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id');
    }



public function childrenBrands()
{
    return $this->hasMany(Brand::class,'category_id');
}

public function attribute()
    {
        return $this->belongsToMany('App\Models\Attribute');
    } 


}
