<?php

namespace App\Http\Controllers;
use App\Models\Shipping;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\City;
use Session;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippings = Shipping::all();
        $cities= City::all();
        return view('admin.shipping.index',compact('shippings','cities'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('admin.shipping.create',compact('cities'));
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
            'location' => 'required',
            'amount'=>'required',
            'days'=>'required'
        ]);

      $shipping= Shipping::create([
          'location'=> $request->location,
          'amount' =>$request->amount,
          'days'=>$request->days
      ]);

        Session::flash('success', 'Designer created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        return view('admin.shipping.edit',compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        $this->validate( $request,[
            'location' => 'required',
            'amount'=> 'required',
            'days'=>'required'
        ]);

        $shipping->location = $request->location;
        $shipping->amount= $request->amount;
        $shipping->days = $request->days;

        $shipping->save();
       
        Session::flash('success', 'Shipping updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        if($shipping){
            $shipping->delete();
            Session::flash('success', 'Shipping deleted successfully');

        }
        return redirect()->back();
    }

    public function shipping(){
        $shipping = Shipping::all();
        return $shipping;
    }

    // update shipping fee while changing shipping address from checkout page
    public function changeShipping(){
        $shipping = Shipping::all();
        $fees=0;
        $days=0;
        $customer_location = Customer::where('user_id',auth()->user()->id)->where('type','shipping')->first();
        if(is_null($customer_location)){
            return json_encode(array('fees'=>$fees,'days'=>$days));
        }else{
            foreach($shipping as $ship){
              
               if(strtolower($ship->location) == strtolower($customer_location->district)){
                $fees= $ship->amount;
                $days = $ship->days;
               }
            }

            return json_encode(array('fees'=>$fees,'days'=>$days));

        }
    }
}
