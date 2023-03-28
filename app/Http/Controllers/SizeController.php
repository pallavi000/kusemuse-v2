<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes= size::all();
        return view('admin.size.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.size.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $this->validate($request, [
            'name' => 'required',
        ]);

        $size = Size::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        
        ]);

        Session::flash('success', 'Size created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Size $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        return view('admin.size.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Size $size)
    {
        // validation
        $this->validate($request, [
            'name' => "required",
        ]);

        $size->name = $request->name;
       
        $size->save();

        Session::flash('success', 'Size updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        if($size){
            $size->delete();

            Session::flash('success', 'Size deleted successfully');
        }

        return redirect()->back();
    }
}
