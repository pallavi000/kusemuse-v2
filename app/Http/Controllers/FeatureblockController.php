<?php

namespace App\Http\Controllers;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Featureblock;

class FeatureblockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = Featureblock::all();
        return view('admin.featureblock.index',compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.featureblock.create');
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
            'title' => 'required',
            'image' => 'required',      
        ]);
        $feature = Featureblock::create([
            'title' => $request->title,
            'image' => 'image.jpg',
            'published_at' => Carbon::now(),
            'link'=>$request->link,
            'detail'=>$request->detail
        ]);

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/feature/', $image_new_name);
            $feature->image = '/storage/feature/' . $image_new_name;
        }
        $feature->save();
        
        Session::flash('success', ' Featureblock added successfully');
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
    public function edit(Featureblock $featureblock)
    {
        $feature = $featureblock;     
        return view('admin.featureblock.edit',compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Featureblock $featureblock)
    {
        
        $feature = $featureblock;   
        $this->validate($request, [
            'title' => 'required',
            
           
        ]);
        $feature->title = $request->title;
        $feature->detail = $request->detail;
        $feature->link = $request->link;
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/feature/', $image_new_name);
            $feature->image = '/storage/feature/' . $image_new_name;

        }

        $feature->save();
        Session::flash('success','featureblock updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Featureblock $featureblock)
    {
        $feature = $featureblock;   
        if($feature){
            if(file_exists(public_path($feature->image))){
                unlink(public_path($feature->image));
            }
            $feature->delete();
            Session::flash('success','featureblock deleted successfully');
        }

        return redirect()->back();
    
    }
}
