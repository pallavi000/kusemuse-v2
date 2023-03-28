<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;

class Transaction extends Model
{
    use HasFactory;

    protected $cast= [
        'seller_id'=>'array'
    ];
    

    protected $guarded = [''];
  

    public function orders(){
        return $this->hasMany(Order::class,'transaction_id','id')->with('product','sizes');
    }


    public function seller_orders(){
        return $this->hasMany(Order::class,'transaction_id','id')->with('product','sizes')->where('seller_id',auth()->user()->id);
    }
    

    public function billing(){
        return $this->belongsTo(Customer::class,'user_id', 'user_id')->where('type','billing');
    
    }
    public function shipping(){
        return $this->belongsTo(Customer::class,'user_id', 'user_id')->where('type','shipping');
    
    }



    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
