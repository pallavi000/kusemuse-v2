<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product_Size;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use App\Models\Transaction;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->is_admin==1){
            $orders= Transaction::with('user','orders')->OrderBy('created_at','desc')->get();
           
        }else{
            $orders= Transaction::with('user','seller_orders')->whereJsoncontains('seller_id',auth()->user()->id)->OrderBy('created_at','desc')->get();          
        }
        return view('admin.order.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product::with('sizes')->first();
  
    }

    public function invoice($id){

        if(auth()->user()->is_admin==1){
            $transaction = Transaction::where('id',$id)->first();
            $customer = Customer::with('user')->where('user_id',$transaction->user_id)->where('type','billing')->first();
            $order= Order::with('product','transaction','user')->where('user_id',$transaction->user_id)->where('transaction_id',$transaction->id)->get();

        }else{
            $transaction = Transaction::where('id',$id)->first();

            $customer = Customer::with('user')->where('user_id',$transaction->user_id)->where('type','billing')->first();

            $order= Order::with('product','transaction','user')->where('user_id',$transaction->user_id)->where('seller_id',auth()->user()->id)->where('transaction_id',$transaction->id)->get();
        }

        $data = compact('order','customer');
        $pdf = PDF::loadView('admin.order.invoice',$data);
        return $pdf->stream();
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
            'detail' => 'required',
            'payment_status'=>'required',
            'total' => 'required',
            
        ]);
        $order = Order::create([
            
            'user_id' => auth()->user()->id,
            'product_id'=>$request->product,
            'detail' => $request->detail,
            'payment_status' => $request->payment_status,
            'total' => $request->total,
           
        ]);

        $order->save();
        
        Session::flash('success', 'order added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($trnx)
    {
    
        if(auth()->user()->is_admin==1){
            $transaction = Transaction::with('orders','billing','shipping','user')->where('id',$trnx)->first();
        }else{
            $transaction = Transaction::with('seller_orders','billing','shipping','user')->whereJsoncontains('seller_id',auth()->user()->id)->where('id',$trnx)->first();
        }
       

        return view('admin.order.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        

        return view('admin.order.edit',compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $txn_id)
    {
        if($request->order_status) {
            $this->validate($request, [
                'order_status' => 'required',
                
            ]);
            //Orders finding
            if(auth()->user()->is_admin==1){
                $orders = Order::where('transaction_id',$txn_id)->get();

            }else{
                $orders = Order::where('transaction_id',$txn_id)->where('seller_id',auth()->user()->id)->get();
            }
            //Loop thorught order
            foreach($orders as $order){
                //Update Product Stock from size table or product table
                $sizestock = Product_Size::where('size_id',$order->size_id)->where('product_id',$order->product_id)->first();
                if($order->order_status=="cancelled"){
                    if(is_null($sizestock)){
                        $product = Product::where('id',$order->product_id)->first();
                        $product->stock -= $order->quantity;
                        $product->save(); 
                    }else{
                        $sizestock->stock -= $order->quantity;
                        $sizestock->save();
                    }  
                }
                    //Order status update
                    $order->order_status = $request->order_status;
                    if($request->order_status=="shipped"){
                        $order->ship_date= Carbon::now();
                    }
                    if($request->order_status=="completed"){
                        $order->delivered_date= Carbon::now();
                        if(auth()->user()->is_admin==1){
                            $order->fulfillment= "By Kusemuse";
                        }else{
                            $order->fulfillment= "By "+auth()->user()->name;
                        }                     
                    }
                    $order->save();              
    
                    //Increase Stock to Previous If Order is reversed from cancelled
                    if($request->order_status=="cancelled"){
                        if(is_null($sizestock)){
                            $product = Product::where('id',$order->product_id)->first();
                            $product->stock += $order->quantity;
                            $product->save(); 
                        }else{
                            $sizestock->stock += $order->quantity;
                            $sizestock->save();
                        }
                    } 
            }      
        } else {
            $this->validate($request, [
                'payment_status' => 'required',
            ]);
            $order->payment_status = $request->payment_status;
            $order->save();
        }

        Session::flash('success', 'order updated successfully');
        return redirect()->back();

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
    }
    public function order(){
       $txn = Transaction::with('orders','user','billing','shipping')->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->get();
       return $txn;
       
    }


    public function ordercancel($id){
        $order = Order::where('id',$id)->where('user_id',auth()->user()->id)->first();

        $sizestock = Product_Size::where('size_id',$order->size_id)->where('product_id',$order->product_id)->first();
        // increase product stock from product-size table or from products table
        if(is_null($sizestock)){
            $product = Product::where('id',$order->product_id)->first();
            $product->stock += $order->quantity;
            $product->save(); 
        }else{
            $sizestock->stock += $order->quantity;
            $sizestock->save();
        }

        $order->order_status = 'cancelled';
        $order->save();
    }


    public function orderTracking(Request $request){
        $user = User::where('email',$request->email)->first();
        if(!is_null($user)){
            $order= Order::with('product','shipping','billing','user')->where('id',$request->orderId)->where('user_id',$user->id)->first();
            if(!is_null($order)){
                return $order;
            }else{
                return response()->json(['Order not found'], 404);
            }
        }else{
            return response()->json(['user not found'], 404);
        }
        
    }
    
}
