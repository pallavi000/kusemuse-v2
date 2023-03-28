<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\User;
use App\Models\Addtocart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product_Size;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Session;
use PDF;
use App\Models\Subscription;



class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('customer')->whereNull('role')->whereNull('is_admin')->get();
        return view('admin.customer.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    // generate invoice for order
    public function invoice($id){
        if(auth()->user()->is_admin==1){
            $order = Order::with('product')->where('user_id',$id)->first(); 
        }else{
            $order = Order::with('product')->where('user_id',$id)->where('seller_id',auth()->user()->id)->first();
        }
        $data = compact('order');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::with('customer')->findorfail($id);
        // dd($users);
        return view('admin.customer.index',compact('users'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user= User::with('customer')->findorfail($id);
        if($user){
            $user->delete();
            Session::flash('success', 'User deleted successfully');
        }
        return redirect()->back();
    }
     
    // Find current user
    public function profile(){
        $user_id = auth()->user()->id;
        $user = User::with('customer')->where('id',$user_id)->first();
        return $user;
    }

    // create customer detail
    public function address(Request $request){
        if($request->note){
            $note = $request->note;
        }else{
            $note = '';
        }

        // billing detail
        Customer::create([
            'street'=> $request->street,
            'city'=> $request->city,
            'district'=> $request->district,
            'provience' => $request->provience,
            'user_id' => auth()->user()->id,
            'phone' =>$request->phone,
            'type'=>'billing'
        ]);

        // shipping detail
        Customer::create([
            'street'=> $request->shiping_street,
            'city'=> $request->shiping_city,
            'district'=> $request->shiping_district,
            'provience' => $request->shiping_provience,
            'user_id' => auth()->user()->id,
            'phone' =>$request->shiping_phone,
            'type'=>'shipping',
        ]);
        return $request->city;
    }

    // edit address page
    public function editaddress(){
        $billing = Customer::where('user_id', auth()->user()->id)->where('type','billing')->first();
        $shipping = Customer::where('user_id', auth()->user()->id)->where('type','shipping')->first();
       return json_encode(array('billing'=>$billing,'shipping'=>$shipping));
    }

    // update customer detail
    public function replaceadd(Request $request){
        if($request->note){
            $user->note = $request->note;
           }

        //    billing detail
            $user = Customer::where('user_id',auth()->user()->id)->where('type','billing')->first();
            $user->provience= $request->provience;
            $user->city = $request->city;
            $user->street = $request->street;
            $user->district = $request->district;
            $user->phone = $request->phone;
            $user->save();

        // shipping detail
        $user = Customer::where('user_id',auth()->user()->id)->where('type','shipping')->first();
        $user->provience= $request->shiping_provience;
        $user->city = $request->shiping_city;
        $user->street = $request->shiping_street;
        $user->district = $request->shiping_district;
        $user->phone = $request->shiping_phone;
        $user->save();

       return $request->street;
    }



    //Payment | moving cart items to orders table
    public function payment(Request $request){
        $cart = Addtocart::with('product')->where('user_id',auth()->user()->id)->get();
        
        // check whether coupon is used by user or not,if coupon is use then decrese the coupon  usages
        $coupon = Coupon::where('id', $request->couponID)->first();
        if(is_null($coupon)) {
        } else {
            if($coupon->usagesLimit && $coupon->usagesLimit==0) {
                return response()->json(['Coupon code not found'], 404);
            }
            if($coupon->usagesLimit && $coupon->usagesLimit>0) {
                $coupon->decrement('usagesLimit');
            }
        }

        // clone cart array
        $cart2 =$cart;

        // gather seller id from multiple orders
        $seller_ids = [];
        foreach($cart as $cart) {

            array_push($seller_ids, $cart->seller_id);

            // check product available stock and if product is out of stock return error 
            $product_size = Product_Size::with('product')->where('product_id',$cart->product_id)->where('size_id',$cart->size_id)->first();
            if(is_null($product_size)){
                $product = Product::findorfail($cart->product_id);
                if($cart->quantity > $product->stock){
                    return response()->json($product->name." quanity can't be greater than available stock.", 400); // Status code here
                }
            }else{
                if($cart->quantity > $product_size->stock){
                    return response()->json($product_size->product->name." quantity can't be greater than available stock.", 400); // Status code here
                }   
            }
        }
        

        $seller_ids = json_encode($seller_ids);



        // create transaction 
        $transaction = Transaction::create([
            'amount' => $request->amount,
            'transaction_id'=>$request->transaction_id,
            'payment_method'=>$request->payment_method,
            'payment_type'=>$request->payment_type,
            'user_id' => auth()->user()->id,
            'shipping_cost'=>$request->fee,
            'note' =>$request->note,
            'seller_id'=>$seller_ids
        ]);

        // for mail
        $mailDetails = [];

        $ship_address = Customer::where('user_id',auth()->user()->id)->where('type','shipping')->first();
        foreach($cart2 as $cart) {

            // check coupon discount
            if($cart->product_id === $request->coupon_pid){
                $total= ($cart->price*$cart->quantity)  - $request->coupon_discount;
                $coupon_discount = $request->coupon_discount;
            }else{
                $total = ($cart->price*$cart->quantity);
                $coupon_discount = 0;
            }

            // increase seller balance of respective seller
            $user= User::findorfail($cart->seller_id);
            $user->balance = $user->balance+ $total;
            $user->save();

        

        //Create order for each cart item
        $order= Order::create([
            'user_id'=>auth()->user()->id,
            'product_id'=> $cart->product_id,
            'total' => $total,
            'payment_status'=> $request->payment_status,
            'order_status'=>'processing',
            'price'=> $cart->price,
            'quantity'=>$cart->quantity,
            'size_id'=> $cart->size_id,
            'color' => $cart->color,
            'coupon_discount' => $coupon_discount,
            'coupon_id' =>$request->couponID,
            'sku' => $cart->sku,
            'seller_id'=>$cart->seller_id,
            'shipping_cost'=> $request->fee,
            'payment_method' =>$request->payment_method,
            'days' => $request->days,
            'discount'=>$cart->product->discount,
            'ipaddress'=> $request->ip(),
            'transaction_id'=>$transaction->id,
            'note'=>$request->note
        ]);
        

        // for MailDetail
        $details= [
            'title'=>'Order Successfully!!',
            'body'=>'Your Order is conformed.
            And we are just as excited as you are!!',
            'price'=> $cart->price,
            'total' => $total+$request->fee,
            'shipping_cost'=> $request->fee,
            'days' => $request->days,
            'quantity'=>$cart->quantity,
            'order_id'=>$order->id,
            'name'=>$cart->product->name
        ];

        array_push($mailDetails,$details);

        
        

        // decrease product stock from product-size  table or from products table
        $product_size = Product_Size::where('product_id',$cart->product_id)->where('size_id',$cart->size_id)->first();
        if(is_null($product_size)){
            $product = Product::findorfail($cart->product_id);
            $product->decrement('stock',$cart->quantity);
        }else{
            $product_size->decrement('stock',$cart->quantity);
        }

        // remove item from cart after moving cart item to order table
        $single_cart= Addtocart::where('id',$cart->id)->first();
        $single_cart->delete();

    }
        //$user= User::all();
        // mail the user after order success
        Mail::to(auth()->user()->email)->send(new TestMail($mailDetails, $transaction,$ship_address));
        return 'success';
    }


    // remove product from addtocart by cart_id
    public function distroy($id){
        $del = Addtocart::where('id',$id)->first();
        $del->delete();  
    }
 
    // change product quantity from addtocart
    public function changecart(Request $request,$id){
        $cart = Addtocart::where('id',$id)->first();
        $cart->quantity= $request->quantity;
        $cart->save();
        return $request->quantity;
    }

    // for guest user
    public function guestremove($id){
        $del = Addtocart::where('id',$id)->first();
        $del->delete();
    }
    // for guest user
    public function cartadd(Request $request,$id){
        $cart = Addtocart::where('id',$id)->first();
        $cart->quantity= $request->quantity;
        $cart->save();
        return $request->quantity;  
    }






    //khalti payment verify
    public function paymentverify(Request $request){
        $args = http_build_query(array(
            'token' => $request->token,
            'amount'  => $request->amount
        ));
        $url = "https://khalti.com/api/v2/payment/verify/";        
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        
        $headers = ['Authorization: Key test_secret_key_937588282caf4d7f9f3d7265b20a6f04'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        
        // Response
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }

    
  

    // order  received
    public function orderreceived($id){
        $orders = Transaction::with('orders')->where('transaction_id',$id)->where('user_id',auth()->user()->id)->first();
        if(is_null($orders)){
            return [];
        }
        return $orders;
    }

    // esewa payment  verify
    public function esewaVerification(Request $request){
        $url = "https://esewa.com.np/epay/transrec";
        $data =[
            'amt'=> $request->amount,
            'rid'=> $request->refId,
            'pid'=>$request->oid,
            'scd'=> 'NP-ES-THAKURIC'
        ];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }


    // email subscription

    public function subscription(Request $request){
        $email = Subscription::where('mail',$request->email)->first();
        if(!is_null($email)){
           return 'Your email already exist in subscription list.';
        }
       $sub =  Subscription::create([
            'mail'=>$request->email
       ]);
        return "Your email has been added to subscription list.";
    }

}
