<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

use Session;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
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
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }

    // add to wishlist |remove from wishlist
    public function addtowish(Request $request){

        $userid = auth()->user()->id;
        if($request->action=="remove") {
            $wishlist= Wishlist::where('user_id',$userid)->where('product_id',$request->product)
            ->where('category_id',$request->category)->first();
            if($wishlist){
                $wishlist->delete();
            }

        } else {
            $wishlist = Wishlist::where('user_id',$userid)->where('product_id',$request->product)
            ->where('category_id',$request->category)->first();
    
           $list = Wishlist::create([
               'user_id' => auth()->user()->id,
               'product_id' => $request->product,
               'category_id' => $request->category,
           ]);
        }
 


    }

    public function wishlist(){
        $userid= auth()->user()->id;
        $wish = Wishlist::with('product','category')->where('user_id',$userid)->get();
       return $wish;
    }
}
