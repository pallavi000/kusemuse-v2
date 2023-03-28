<?php

namespace App\Http\Controllers;
use App\Models\Addtocart;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Product_Size;
use App\Models\Wishlist;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AddtocartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addtocarts = Addtocart::with('product','user','category')->get();

        return view('admin.addtocart.index',compact('addtocarts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.addtocart.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required',
            
        ]);
        $addtocart = Addtocart::create([
            'quantity' => $request->quantity,
            'product_id' => $request->product,
            'category_id' =>$request->category,
            'user_id'   => $request->user,
            'published_at' => Carbon::now(),
  
        ]);

        $addtocart->save();
        
        Session::flash('success', 'cart added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ADDtocart $addtocart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Addtocart $addtocart)
    {
        return view('admin.addtocart.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Addtocart $addtocart)
    {
        $this->validate($request, [
            'quantity' => 'required',
            'product'  =>'required',
 
        ]);

            $addtocart->quantity = $request->quantity;
            $addtocart->product_id = $request->product;
            $addtocart->user_id = $request->addtocart;

            $addtocart->save();

            Session::flash('success', 'cart updated successfully');
            return redirect()->back();


    

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Addtocart $addtocart)
    {
        //
    }


    // add to cart
    public function cart(Request $request){
        $userid = auth()->user()->id;

        $cart = Addtocart::where('user_id',$userid)->where('product_id',$request->product)->where('color',$request->color)->where('size_id',$request->size)->first();
        // if product is already in cart then increase the product quantity else add to cart
        if(!is_null($cart)) {
            $cart->increment('quantity');
        } else {
            if($request->quantity) {
                $quantity = $request->quantity;
            } else {
                $quantity = 1;
            }
            $addtocart = Addtocart::create([
                'quantity' => $quantity,
                'product_id' => $request->product,
                'user_id'   => auth()->user()->id,
                'category_id'=>$request->category,
                'published_at' => Carbon::now(),
                'color' => $request->color,
                'size_id' => $request->size,
                'price' =>$request->price,
                'sku' =>$request->sku,
                'seller_id' => $request->seller_id,
            ]);
        }
    }

    // cart count  and add right stock to cart array
    public function count(){
        $userid= auth()->user()->id;
        $carts = Addtocart::with('product','sizes','product_size')->where('user_id',$userid)->get();
        foreach($carts as $cart){
            // if product has size then use stock from product_size table else use stock from product table

            $product_size = Product_Size::where('product_id',$cart->product_id)->where('size_id',$cart->size_id)->first();
            if(is_null($product_size)){
                $product = Product::findorfail($cart->product_id);
                $cart["stock"] = $product->stock;
            }else{
                $cart["stock"] = $product_size->stock;
            }

        }
      
      return $carts;
      

    }


    // wishlilst count
    public function wishcount(){
        $userid= auth()->user()->id;
      $wish = Wishlist::with('product','sizes','product_size')->where('user_id',$userid)->get();
      return $wish;

    }



    // guest user add to cart
    public function guestaddtocart(Request $request){
        $guest_id = $request->guest_id;
        $cart = Addtocart::where('user_id',$guest_id)->where('product_id',$request->product)->where('color',$request->color)->where('size_id',$request->size)->first();
         // if product is already in cart then increase the product quantity else add to cart
        if(!is_null($cart)) {
            $cart->increment('quantity');
        } else {

            if($request->quantity) {
                $quantity = $request->quantity;
            } else {
                $quantity = 1;
            }
            $addtocart = Addtocart::create([
                'quantity' => $quantity,
                'product_id' => $request->product,
                'user_id'   => $guest_id,
                'category_id'=>$request->category,
                'published_at' => Carbon::now(),
                'color' => $request->color,
                'size_id' => $request->size,
                'price' =>$request->price,
                'sku' =>$request->sku,
                'seller_id' => $request->seller_id,
            ]);
        }

    }


    // guest cart count
   public function guestcount($id){
    $guest_id= $id;
    $cart = Addtocart::with('product','sizes','product_size')->where('user_id',$guest_id)->get();
    return $cart; 
    } 
}
