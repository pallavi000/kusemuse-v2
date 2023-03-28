<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use App\Models\User;
class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designers =  Designer::all();
        return view('admin.designer.index',compact('designers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.designer.create');
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
            'name' => 'required'
        ]);

      $designer = Designer::create([
          'name'=> $request->name,
          'slug'=> Str::slug($request->name, '-'),
          'seller_id'=> auth()->user()->id
      ]);

        
        Session::flash('success', 'Designer created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function show(Designer $designer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function edit(Designer $designer)
    {
        return view('admin.designer.edit',compact('designer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designer $designer)
    {
        $this->validate( $request,[
            'name' => 'required',
        ]);

        $designer->name = $request->name;
        $designer->slug= Str::slug($request->name, '-');
        $user= User::where('id',$designer->seller_id)->first();
        if(!is_null($user)){
            $user->name = $request->name;
            $user->save();
        }

        $designer->save();
       
        Session::flash('success', 'Designer updated successfully');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designer $designer)
    {
        if($designer){
            $products = Product::where('designer_id',$designer->id)->get();
            $users = User::where('id',$designer->seller_id)->first();
            // delete user related to designer
            if(!is_null($users)){
                $users->delete();

            }
            // delete products of respective designer
            foreach($products as $product){
                $product->visible=false;
                $product->save();
            }
            $designer->delete();
            Session::flash('success', 'Designer deleted successfully');
        }
        return redirect()->back();
    }
}
