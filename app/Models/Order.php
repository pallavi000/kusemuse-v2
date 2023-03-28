<?php

namespace App\Models;
use App\Models\User;
use App\Models\Product;
use App\Models\Size;
use App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product')->with('brand','color');
    }
    
    public function sizes(){
        return $this->belongsTo(Size::class,'size_id','id');
    }

    public function review(){
        return $this->belongsTo('App\Models\Review');
    }

    public function transaction(){
        return $this->belongsTo('App\Models\Transaction');
    }

    public function billing(){
        return $this->belongsTo(Customer::class,'user_id', 'user_id')->where('type','billing');
    
    }
    public function shipping(){
        return $this->belongsTo(Customer::class,'user_id', 'user_id')->where('type','shipping');
    
    }
    
    
}
