<?php

namespace App\Http\Controllers;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Session;
class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors= Color::all();
        return view('admin.color.index',compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.color.create');
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
            'name'=> 'required',

        ]);

        $color = Color::create([
            'name' => $request->name,
            'slug'=> Str::slug($request->name,'-'),
        ]);

        Session::flash('success', 'Color created successfully');
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
    public function edit(Color $color)
    {
        
        return view('admin.color.edit',compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Color $color)
    {
        $this->validate($request, [
            'name' => "required",
        ]);

        $color->name = $request->name;
        // $color->slug = str_replace(" ", "-", $request->slug);
        $color->slug = Str::slug($request->name, '-');
       
       
        $color->save();

        Session::flash('success', 'Color updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        if($color){
            $color->delete();

            Session::flash('success', 'Color deleted successfully');
        }

        return redirect()->back();
    
    }
}
