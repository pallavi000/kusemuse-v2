<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Http\Request;
use Session;


class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->is_admin == 1){
            $withdraws = Withdraw::all();
        }else{
            $withdraws = Withdraw::where('user_id',auth()->user()->id)->get();
        }


        return view('admin.withdraw.index', compact('withdraws'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->is_admin!=1){
            return view('admin.withdraw.create');
        }else{
            return redirect('/admin/withdraw');
        }
        

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
            'detail'=> 'required',
            'amount'=>'required',
            'payment_method'=> 'required'
        ]);

        if(auth()->user()->balance < $request->amount){
            return redirect()->back()->withErrors(['msg' => 'request amount is greater than total balance']);
        }

        Withdraw::create([
            'user_id'=> auth()->user()->id,
            'status'=>'processing',
            'account_detail'=>$request->detail,
            'payment_method'=>$request->payment_method,
            'amount'=>$request->amount
        ]);

        $user = User::findorfail(auth()->user()->id);
        $user->balance = $user->balance- $request->amount;
        $user->save();

        Session::flash('success', 'Withdraw requested successfully');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        $withdraw->status = $request->status;
        $withdraw->save();

        Session::flash('success', 'Withdraw updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }


    public function withdraw(Request $request){
        Withdraw::create([
            'user_id'=> auth()->user()->id,
            'status'=>'processing',
            'account_detail'=>$request->detail,
            'payment_method'=>$request->paymentMethod,
            'amount'=>$request->amount
            
        ]);
        return 'success';
    }
}
