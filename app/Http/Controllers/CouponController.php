<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Session;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->is_admin==1){
            $coupons = Coupon::with('product')->get();
        }else{
            $coupons = Coupon::with('product')->where('seller_id',auth()->user()->id)->get();
        }
        return view('admin.coupon.index',compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->is_admin==1){
            $products= Product::all();
 
        }else{
            $products= Product::where('seller_id',auth()->user()->id)->get();
        }

        return view('admin.coupon.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $this->validate($request,[
            'product_id'=> "required",
            'expire_date'=> "required",
            "type" => "required",
            "value"=>"required",
            "title"=>"required",    
        ]);
       
        $product= Product::where('id',$request->product_id)->first();
        $slug = $product->slug;
        // coupon code generate
        $slug = substr($slug,0, 10);
        $coupon= $slug.rand(0, 1000);

        if($request->single){
            $single= 1;

        }else{
            $single = 0;
        }



        $coupon = Coupon::create([
            'product_id'=> $request->product_id,
            'title'=>$request->title,
            'expire_date'=> $request->expire_date,
            'type' =>$request->type,
            'coupon_code'=>$coupon,
            'value' =>$request->value,
            'minimumSpend'=>$request->minimumSpend,
            'maximumSpend'=>$request->maximumSpend,
            'usagesLimit'=> $request->limit,
            'single' => $single,
            'seller_id'=>auth()->user()->id
        ]);
        Session::flash('success', 'Coupon created successfully');
        return redirect()->back();
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        if(auth()->user()->is_admin == 1){
            $coupon = Coupon::with('product')->findorfail($coupon->id);
        }else{
            $coupon = Coupon::with('product')->where('seller_id', auth()->user()->id)->findorfail($coupon->id);
        }
        
        if(auth()->user()->is_admin==1){
            $products = Product::all();
 
        }else{
            $products = Product::where('seller_id',auth()->user()->id)->get();
        }

        return view('admin.coupon.edit',compact('products','coupon'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $this->validate($request,[
            'product_id'=> "required",
            'expire_date'=> "required",
            "type" => "required",
            "value"=>"required"
        ]);
       
        $product= Product::where('id',$request->product_id)->first();
        $slug = $product->slug;

        $slug = substr($slug,0, 10);
        $coupon1= $slug.rand(0, 1000);

        
        if($request->single){
            $single= 1;
        }else{
            $single = 0;
        }

            $coupon->product_id = $request->product_id;
            $coupon->expire_date= $request->expire_date;
            $coupon->type =$request->type;
            $coupon->coupon_code = $coupon1;
            $coupon->value =$request->value;
            $coupon->minimumSpend=$request->minimumSpend;
            $coupon->maximumSpend =$request->maximumSpend;
            $coupon->usagesLimit= $request->limit;
            $coupon->single = $single;
            $coupon->seller_id=auth()->user()->id;
            $coupon->save();

        Session::flash('success', 'Coupon updated successfully');
        return redirect()->back();
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        if($coupon){
            $coupon->delete();
            Session::flash('success', 'Coupon deleted successfully');
            
        }
        return redirect()->back();
    }

    // coupon apply from checkout page 
    public function coupon(Request $request){
        $today = Date("Y-m-d");
       $coupon = Coupon::where('coupon_code', $request->coupon)->where('expire_date','>',$today)->first();
        //    if coupon is one time use then check user has already used coupon or not
       if($coupon) {
           if($coupon->single===1) {
                $order = Order::where('coupon_id', $coupon->id)->where('user_id', auth()->user()->id)->first();
                if($order) {
                    return response()->json(['Coupon code not found'], 404);
                } else {
                    return $coupon;
                }
           }
           return $coupon;
        
       } else {
        return response()->json(['Coupon code not found'], 404); // Status code here
       }
       
    }
}
