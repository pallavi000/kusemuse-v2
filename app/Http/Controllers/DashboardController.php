<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Review;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adminHome(){
       
        if(auth()->user()->is_admin==1){

            $income = Order::where('created_at','>',Carbon::today()->subDays(1))->sum('total');

            $newOrders = Order::where('order_status','processing')->get();
            
            $total = Order::sum('total');
    
            $unpublished_review = Review::where('status','unpublish')->get();
    
            $unpublished_review = count($unpublished_review);

        }else{

        
        $income = Order::where('seller_id',auth()->user()->id)->where('created_at','>',Carbon::today()->subDays(1))->sum('total');

        $newOrders = Order::where('order_status','processing')->where('seller_id',auth()->user()->id)->get();
        
        $total = Order::where('seller_id',auth()->user()->id)->sum('total');

        $unpublished_review = Review::where('status','unpublish')->get();
        
   

        $unpublished_review = count($unpublished_review);
        }

        return view('admin.dashboard',compact('newOrders','income','total','unpublished_review'));
    }

   
    
}
