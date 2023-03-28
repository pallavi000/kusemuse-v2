<?php

namespace App\Models;
use App\Models\Customer;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded =  [];

    public function user(){
        return $this->belongsTo('App\Models\User');
 
    }
}
